<?php

namespace App\Modules\Mollie;

use App\App;
use \Mollie\Api\MollieApiClient;
use \Mollie\OAuth2\Client\Provider\Mollie;
use \League\OAuth2\Client\Provider\GenericProvider;
use \League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class MollieService
{
    /**
     * @var App
     */
    protected $app;

    /**
     * @var MollieApiClient
     */
    protected $mollie;

    /**
     * @var string
     */
    public $clientId;

    /**
     * @var string
     */
    public $clientSecret;
    /**
     * @var array
     */
    public $clientScope;

    /**
     * @var string
     */
    public $clientRedirectUrl;

    /**
     * @var string
     */
    public $apiKey;

    /**
     * @var string
     */
    public $accessKey;

    /**
     * @var string
     */
    public $accountId;


    /**
     * @var string
     */
    public $profileId;


    /**
     * @var string
     */
    public $organisationId;


    /**
     * @var string
     */
    public $partnerProfileId;

    /**
     * @var string
     */
    public $partnerOrganisationId;
    /**
     * @var string
     */
    public $paymentUrl;

    /**
     * @var string
     */
    public $redirectUrl;


    /**
     * @var string
     */
    public $webhookUrl;

    /**
     * @var string
     */
    public $testMode;

    /**
     * @var array
     */
    public $request;

    /**
     * @var array
     */
    public $response;

    /**
     * @var ApiException
     */
    public $apiErrror;

    /**
     * @var bool
     */
    public $success = false;

    /**
     * @var string
     */
    public $transactionId;

    /**
     * @var array
     */

    public $params = [
        ['clientId', 'mollie_client_id'],
        ['clientSecret', 'mollie_client_secret'],
        ['clientRedirectUrl', 'mollie_client_redirect_url', true, 'responseUrl'],
        ['apiKey', 'transaction_api_key'],
        ['accessKey', 'transaction_access_key'],
        ['accountId', 'transaction_account_id'],
        ['profileId', 'transaction_profile_id'],
        ['organisationId', 'transaction_organisation_id'],
        ['partnerProfileId', 'transaction_partner_profile_id'],
        ['partnerOrganisationId', 'transaction_partner_organisation_id'],
        ['redirectUrl', 'transaction_redirect_url', true, 'redirectUrl'],
        ['webhookUrl', 'transaction_webhook_url', true, 'webhookUrl'],
        ['testMode', 'transaction_test_mode'],
        ['transactionId', 'transaction_id']
    ];

    public $arrayParams = [
        ['clientScope', 'mollie_client_scopes']
    ];
   /**
     * @var array
     */

    public $scopes = [
        'payments.read',
        'payments.write',
        'refunds.read',
        'refunds.write',
        'customers.read',
        'customers.write',
        'mandates.read',
        'mandates.write',
        'subscriptions.read',
        'subscriptions.write',
        'profiles.read',
        'profiles.write',
        'invoices.read',
        'settlements.read',
        'orders.read',
        'orders.write',
        'shipments.read',
        'shipments.write',
        'organizations.read',
        'organizations.write',
        'onboarding.read',
        'onboarding.write',
        'payment-links.read',
        'payment-links.write',
        'balances.read',
        'terminals.read',
        'terminals.write',
    ];

    public function __construct(
        App $app
    ) {
        $this->app =  $app;
        $this->clientScope =  [
            'payments.read',
            'payments.write',
        ];
        $this->loadParams();
        $this->saveParams();
        if ($this->apiKey && $this->accessKey) {
            try {
                $this->mollie = new MollieApiClient();
                $this->mollie->setApiKey($this->apiKey);
                $this->mollie->setAccessToken($this->accessKey);
                if ($app->getData["submit"]) {
                    $amount = $app->postData['transaction_amount'] ? number_format($app->postData['transaction_amount'])  : 0.00;
                    $description = $app->postData['transaction_decription'] ? $app->postData['transaction_decription'] : "Test Payment";
                    $applicationFeePercent = $app->postData['transaction_receiver_application_fee'] ? number_format($app->postData['transaction_receiver_application_fee'])  : 0.00;
                    $this->pay($amount, $applicationFeePercent, $description, []);
                }
                if ($app->getData["success"]) {
                    $this->success = true;
                    $this->transactionId = $app->postData['id'] ? $app->postData['id'] : $this->transactionId;
                    if ($this->transactionId) {
                        $this->response =  $this->getTransactionById($this->transactionId);
                    }
                }
                if ($app->getData["connect"]) {
                    $this->connectToMolly();
                }
            } catch (\Mollie\Api\Exceptions\ApiException $e) {
                $this->apiErrror = $e;
                $this->app->adminMail("Mollie API error on " . $this->app->getConfig("domain"), $e->getMessage());
                return;
            }
        }
    }
    private function saveParams()
    {
        foreach ($this->params as $param) {
            $this->app->setCookie($param[1], $this->{$param[0]});
        }
        foreach ($this->arrayParams as $arrayParam) {
            $this->app->setCookie($arrayParam[1], $this->{$arrayParam[0]}, true);
        }
    }

    private function loadParams()
    {
        foreach ($this->params as $param) {
            $key = $param[0];
            $paramName = $param[1];
            $value = $this->getParam($paramName, ($param[2] ? true : false), ($param[3] ? $param[3] : null));
            $this->$key =  $value;
        }
        foreach ($this->arrayParams as $arrayParam) {
            $key = $arrayParam[0];
            $paramName = $arrayParam[1];
        //    $value = $this->getArrayParam($paramName, ($arrayParam[2] ? true : false), ($arrayParam[3] ? $arrayParam[3] : null));
            $this->$key =  $value;
        }
    }

    private function getParam(string $parameterName, bool $configAttributeAsFallback = false, string $configAttribute = null)
    {
        $value = $this->app->postData[$parameterName] ? $this->app->postData[$parameterName] :  $this->app->getCookie($parameterName, $configAttributeAsFallback, $configAttribute);
        return $value;
    }
    private function getArrayParam(string $parameterName, bool $configAttributeAsFallback = false, string $configAttribute = null)
    {
        $value = $this->app->postData[$parameterName] ? $this->app->postData[$parameterName] :  $this->app->getCookie($parameterName, $configAttributeAsFallback, $configAttribute, true);
        return $value;
    }
    public function pay(float $amount, float $applicationFeePercent,  string $description, array $metadata)
    {

        $applicationFeeAmount = 0;
        if ($applicationFeePercent > 0) {
            $applicationFeeAmount = strval(number_format((($amount / 100) * $applicationFeePercent), 2, '.', ''));
        }

        $payment = [
            "profileId" => $this->profileId,
            "testmode" => ($this->testMode === 0 || $this->testMode === false)  ? false : true,
            "amount" => [
                "currency" => "EUR",
                "value" => strval(number_format($amount, 2, '.', ''))
            ],
            "description" => $description,
            "redirectUrl" => $this->redirectUrl,
            "webhookUrl" => $this->webhookUrl,
            "cancelUrl" => $this->webhookUrl,
            "metadata" => $metadata,
        ];


        if ($applicationFeeAmount  > 0) {
            $payment["applicationFee"] = [
                "amount" => [
                    "currency" => "EUR",
                    "value" => $applicationFeeAmount
                ],
                "description" => "Application Fee - " . $description,
            ];
        }

        $this->request = $payment;

        $this->paymentUrl  = null;
        try {
            $payment = $this->mollie->payments->create($payment);
            $this->response = $payment;
            $this->transactionId = $this->response->id;

            $this->paymentUrl = $payment->getCheckoutUrl();
            $this->saveParams();
        } catch (\Mollie\Api\Exceptions\ApiException $e) {
            $this->apiErrror = $e;
            $this->app->adminMail("Mollie API - pay error on " . $this->app->getConfig("domain"), $e->getMessage());
        }
        return  $this->paymentUrl;
    }

    public function getTransactionById(string $transactionId)
    {
        try {
            $payment = $this->mollie->payments->get($transactionId);
        } catch (\Mollie\Api\Exceptions\ApiException $e) {
            $this->apiErrror = $e;
            $this->app->adminMail("Mollie API - getTransactionById error on " . $this->app->getConfig("domain"), $e->getMessage());
            return;
        }
        return $payment;
    }

    public function open_window($url)
    {
        echo '<script type="text/javascript">window.open("' . $url . '");</script>';
    }


    public function checkWebHook()
    {
        $this->transactionId = $this->app->postData['id'] ? $this->app->postData['id'] : $this->transactionId;
        if ($this->transactionId) {
            $this->response =  $this->getTransactionById($this->transactionId);
            $this->app->adminMail("Mollie API - checkWebHook on " . $this->app->getConfig("domain"),  $this->response);
        }
    }


    private function getMollyAccess()
    {
        $provider = new GenericProvider([
            'clientId' => $this->clientId,    // The client ID assigned to you by the provider
            'clientSecret' => $this->clientSecret,    // The client password assigned to you by the provider
            'urlAuthorize' => 'https://www.mollie.com/oauth2/authorize',
            'urlAccessToken' => 'https://api.mollie.com/oauth2/tokens',
            'urlResourceOwnerDetails' => 'https://api.mollie.com/v2/organizations/me',
            'scope' => $this->clientScope
        ]);

        if (!isset($_GET['code'])) {
            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $authorizationUrl = $provider->getAuthorizationUrl([
                'scope' => $this->clientScope,
                'redirectUrl' => $this->redirectUrl,
                'webhookUrl' => $this->webhookUrl,
            ]);

            // Get the state generated for you and store it to the session.
            $_SESSION['oauth2state'] = $provider->getState();

            // Redirect the user to the authorization URL.
            header('Location: ' . $authorizationUrl);
            exit;
        } elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

            try {

                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code'],
                    'redirectUrl' => $this->redirectUrl,
                    'webhookUrl' => $this->webhookUrl,
                    'scope' => $this->clientScope,
                ]);

                // We have an access token, which we may use in authenticated
                // requests against the service provider's API.
                echo 'Access Token: ' . $accessToken->getToken() . "<br>";
                echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
                echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
                echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";

                // Using the access token, we may look up details about the
                // resource owner.
                $resourceOwner = $provider->getResourceOwner($accessToken);

                var_export($resourceOwner->toArray());

                return [
                    'access' => true,
                    'accessToken' => $accessToken,
                    'provider' => $provider,
                    'resourceOwner' => $resourceOwner
                ];
            } catch (IdentityProviderException $e) {
                $message = $e->getMessage();
                return [
                    'access' => false,
                    'message' => $message
                ];
            }
        }
    }


    private function connectToMolly()
    {

        $provider = new Mollie([
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
            'redirectUri' => $this->clientRedirectUrl,
            'errorUri' => $this->clientRedirectUrl,
        ]);

        // If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {
            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $authorizationUrl = $provider->getAuthorizationUrl([
                // Optional, only use this if you want to ask for scopes the user previously denied.
                'approval_prompt' => 'force',

                // Optional, a list of scopes. Defaults to only 'organizations.read'.
                'scope' => $this->clientScope,
            ]);

            // Get the state generated for you and store it to the session.
            $_SESSION['oauth2state'] = $provider->getState();

            // Redirect the user to the authorization URL.
            header('Location: ' . $authorizationUrl);
            exit;
        } // Check given state against previously stored one to mitigate CSRF attack
        elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        } else {
            try {
                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                // Using the access token, we may look up details about the resource owner.
                $resourceOwner = $provider->getResourceOwner($accessToken);
            } catch (dentityProviderException $e) {
                // Failed to get the access token or user details.
                exit($e->getMessage());
            }
        }

        return $provider;
    }
}
