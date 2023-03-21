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

    public function __construct(
        array $config
    ) {
        $this->config = $config;
        $this->getData = $_GET;
        $this->postData = $_POST;
        $this->mollie = new MollieService($this);
        $this->chat = new ChatService($this);

        if ($this->getData["page"]) {
            $this->showPage($this->getData["page"]);
        }
    }

    /** get config value for attribute
     */
    public function showPage(string $pageName)
    {
        $this->pageName = $pageName;
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

    public function payToMollie($amount)
    {
        $redirectUrl = $this->mollie->pay($amount, 10, "Test Transaction", [
            "additionalData" => "Test Datza"
        ]);
        return $redirectUrl;
    }

    public function setCookie(string $storageName, string $value = null)
    {
        if ($value != null && $storageName != null) {
            setcookie($storageName,  $value);
            return true;
        }
        return false;
    }

    public function getCookie(string $storageName, bool $configAttributeAsFallback = false, string $configAttribute = null)
    {
        $configAttribute = $configAttribute ? $configAttribute : $storageName;

        $configValue = $this->getConfig($configAttribute);

        return $_COOKIE[$storageName] ? $_COOKIE[$storageName] : $configValue;
    }


    public function arrayToHtmlTable($arrayData, string $title = null)
    {
        $html = $title != null ? '<h3>' .$title. '</h3>'  : '' ;
        if (is_array($arrayData) || is_object($arrayData)) {
            // start table
            $html .='<table><tbody>';
            if(is_object($arrayData) || count($arrayData)){
            // data rows
            foreach ($arrayData as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $html .= '<tr>';
                    $html .= '<th>' . htmlspecialchars($key) . '</th><td>' . $this->arrayToHtmlTable($value) . '</td>';
                    $html .= '</tr>';
                } else {
                    if(is_bool($value)){
                        $value = $value ? 'true' : 'false';
                    }
                    if(! $value){
                        $value = 'none';
                    }
                    $html .= '<tr>';
                    $html .= '<th>' . htmlspecialchars($key) . '</th><td>' . htmlspecialchars($value) . '</td>';
                    $html .= '</tr>';
                }
            }
            }else{
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
}
