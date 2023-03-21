<?php

namespace App\Modules\Mollie;

use App\App;
use \Mollie\Api\MollieApiClient;

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

    public function __construct(
        App $app
    ) {
        $this->app =  $app;
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
    }

    private function loadParams()
    {
        foreach ($this->params as $param) {
            $key = $param[0];
            $paramName = $param[1];
            $value = $this->getParam($paramName, ($param[2] ? true : false), ($param[3] ? $param[3] : null));
            $this->$key =  $value;
        }
    }

    private function getParam(string $parameterName, bool $configAttributeAsFallback = false, string $configAttribute = null)
    {
        $value = $this->app->postData[$parameterName] ? $this->app->postData[$parameterName] :  $this->app->getCookie($parameterName, $configAttributeAsFallback, $configAttribute);
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
            "metadata" => $metadata,
        ];


        if ($applicationFeeAmount  > 0) {
            $payment["applicationFee"] = [
                "amount" => [
                    "currency" => "EUR",
                    "value" => $applicationFeeAmount
                ],
                "description" => $description,
            ];
        }

        $this->request = $payment;

        $this->paymentUrl  = null;
        try {
            $payment = $this->mollie->payments->create($payment);
            $this->response = $payment;
            $this->transactionId = $this->response->id ;

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
}
