<?php

namespace App;

require_once __DIR__ . '/Config/Autoload.php';


use \App\Modules\Mollie\MollieService;
use \App\Modules\Chat\ChatService;

class App
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    public $postData;

    /**
     * @var array
     */
    public $getData;

    /**
     * @var MollieService
     */
    public $mollie;

    /**
     * @var ChatService
     */
    public $chat;

    /**
     * @var bool
     */
    public $homeButtonVisible = false;

    /**
     * @var string
     */
    public $pageName = 'start';

    /**
     * @var array
     */

    private $cookieOptions;

    public function __construct(
        array $config
    ) {

        /*
        $this->cookieOptions = array(
            'expires' => time() + 60 * 60 * 24 * 30,
            'path' => '/',
            'domain' => '.dont-use.com', // leading dot for compatibility or use subdomain
            'secure' => true,     // or false
            'httponly' => true,    // or false
            'samesite' => 'Strict' // None || Lax  || Strict
        );
        */

        $this->config = $config;
        $this->getData = $_GET;
        $this->postData = $_POST;
        if ($this->getData["page"]) {
            $this->showPage($this->getData["page"]);
        }
    }

    /** get config value for attribute
     */
    public function showPage(string $pageName)
    {
        $this->pageName = $pageName;
        if ($this->pageName === "chat") {
            $this->chat = new ChatService($this);
        }
        if ($this->pageName === "mollie") {
            $this->mollie = new MollieService($this);
        }
    }

    /** get config value for attribute
     */
    public function getConfig(string $attributeName)
    {


        return $this->config[$attributeName] ? $this->config[$attributeName] : null;
    }

    /**
     * set config value for attribute
     */
    public function setConfig(string $attributeName, $value)
    {
        return $this->config[$attributeName] = $value;
    }

    public function mail($email, $subject, $message)
    {
        $message = wordwrap($message, 70);
        mail($email, $subject, $message);
    }
    public function adminMail($subject, $message)
    {
        $message = wordwrap($message, 70);
        mail($this->getConfig("email"), $subject, $message);
    }

    public function payToMollie($amount)
    {
        $redirectUrl = $this->mollie->pay($amount, 10, "Test Transaction", [
            "additionalData" => "Test Datza"
        ]);
        return $redirectUrl;
    }

    public function setCookie(string $storageName, $value = null, bool $parseArray = false)
    {

        if ($value != null && $storageName != null) {
            if ($parseArray) {
                $value = json_encode($value);
            }
            try {
                setcookie($storageName,  $value, $this->cookieOptions);
            } catch (\Exception $e) {
                print_r($e);
                return false;
            }
            return true;
        }
        return false;
    }

    public function getCookie(string $storageName, bool $configAttributeAsFallback = false, string $configAttribute = null, bool $parseArray = false)
    {
        $configAttribute = $configAttribute ? $configAttribute : $storageName;
        $configValue = $this->getConfig($configAttribute);
        $value = $_COOKIE[$storageName] ? $_COOKIE[$storageName] : $configValue;
        if ($value  && $parseArray) {
            $value  = json_decode($value);
        }
        return  $value;
    }


    public function arrayToHtmlTable($arrayData, string $title = null)
    {
        $html = $title != null ? '<h3>' . $title . '</h3>'  : '';
        if (is_array($arrayData) || is_object($arrayData)) {
            // start table
            $html .= '<table><tbody>';
            if (is_object($arrayData) || count($arrayData)) {
                // data rows
                foreach ($arrayData as $key => $value) {
                    if (is_array($value) || is_object($value)) {
                        $html .= '<tr>';
                        $html .= '<th>' . htmlspecialchars($key) . '</th><td>' . $this->arrayToHtmlTable($value) . '</td>';
                        $html .= '</tr>';
                    } else {
                        if (is_bool($value)) {
                            $value = $value ? 'true' : 'false';
                        }
                        if (!$value) {
                            $value = 'none';
                        }
                        $html .= '<tr>';
                        $html .= '<th>' . htmlspecialchars($key) . '</th><td>' . htmlspecialchars($value) . '</td>';
                        $html .= '</tr>';
                    }
                }
            } else {
                $html .= '<tr>';
                $html .= '<th>No Data</th>';
                $html .= '</tr>';
            }
            // finish table and return it
            $html .= '</tbody></table>';
        } else {
            $dataString = $arrayData ? print_r($arrayData, true) : 'No Data';
            $html .= $dataString;
        }
        return $html;
    }


    public function arrayToHtmlCheckboxes($arrayData, string $name, string $label = null, bool $labelAsH3 = null)
    {
        $html = $labelAsH3 && $label != null ? '<h3>' . $label . '</h3>'  : '';
        if (is_array($arrayData) || is_object($arrayData)) {
            // start table
            $html .= '<fieldset class="horizontal-flex flex-wrap flex-gap">';
            $html .= !$labelAsH3 && $label != null ? '<legend>' . $label . '</legend>'  : '';
            foreach ($arrayData as $key => $value) {
                $html .= '<div class="flex-item checkbox-container"><input  type="checkbox" name="' . $name . '[]" value="' . $value . '"><label>' . $value . '</label></div>';
            }
            $html .= '</fieldset>';
        }
        return $html;
    }
}
