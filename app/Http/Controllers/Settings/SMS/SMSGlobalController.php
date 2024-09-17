<?php

namespace App\Http\Controllers\Settings\SMS;

use App\Http\Controllers\Controller;
use Exception;
use Tzsk\Sms\Facades\Sms;

class SMSGlobalController extends Controller
{
    /**
     * @var mixed
     */
    public $driver;

    /**
     * @param $gateway
     */
    public function __construct($gateway)
    {
        $this->driver = $gateway->driver;

        if ($gateway->driver == 'sns') {
            $config = [
                'key'    => $gateway->data['key'],
                'secret' => $gateway->data['secret'],
                'region' => $gateway->data['region'],
                'sender' => $gateway->data['sender'],
                'type'   => $gateway->data['type'],
            ];
        } elseif ($gateway->driver == 'textlocal') {
            $config = [
                'url'      => 'http://api.textlocal.in/send/', // Country Wise this may change.
                'username' => $gateway->data['username'],
                'hash'     => $gateway->data['hash'],
                'sender'   => $gateway->data['sender'],
            ];
        } elseif ($gateway->driver == 'twilio') {
            $config = [
                'sid'   => $gateway->data['sid'],
                'token' => $gateway->data['token'],
                'from'  => $gateway->data['from'],
            ];
        } elseif ($gateway->driver == 'clockwork') {
            $config = [
                'key' => $gateway->data['key'],
            ];
        } elseif ($gateway->driver == 'linkmobility') {
            $config = [
                'url'      => 'http://simple.pswin.com', // Country Wise this may change.
                'username' => $gateway->data['username'],
                'password' => $gateway->data['password'],
                'sender'   => $gateway->data['sender'],
            ];
        } elseif ($gateway->driver == 'melipayamak') {
            $config = [
                'username' => $gateway->data['username'],
                'password' => $gateway->data['password'],
                'from'     => $gateway->data['from'],
                'flash'    => $gateway->data['flash'],
            ];
        } elseif ($gateway->driver == 'melipayamakpattern') {
            $config = [
                'username' => $gateway->data['username'],
                'password' => $gateway->data['password'],
            ];
        } elseif ($gateway->driver == 'kavenegar') {
            $config = [
                'apiKey' => $gateway->data['apiKey'],
                'from'   => $gateway->data['from'],
            ];
        } elseif ($gateway->driver == 'smsir') {
            $config = [
                'url'       => 'https://ws.sms.ir/',
                'apiKey'    => $gateway->data['apiKey'],
                'secretKey' => $gateway->data['secretKey'],
                'from'      => $gateway->data['from'],
            ];
        } elseif ($gateway->driver == 'tsms') {
            $config = [
                'url'      => 'http://www.tsms.ir/soapWSDL/?wsdl',
                'username' => $gateway->data['username'],
                'password' => $gateway->data['password'],
                'from'     => $gateway->data['from'],
            ];
        } elseif ($gateway->driver == 'farazsms') {
            $config = [
                'url'      => '188.0.240.110/services.jspd',
                'username' => $gateway->data['username'],
                'password' => $gateway->data['password'],
                'from'     => $gateway->data['from'],
            ];
        } elseif ($gateway->driver == 'smsgatewayme') {
            $config = [
                'apiToken' => $gateway->data['apiToken'],
                'from'     => $gateway->data['from'],
            ];
        } elseif ($gateway->driver == 'smsgateway24') {
            $config = [
                'url'      => 'https://smsgateway24.com/getdata/addsms',
                'token'    => $gateway->data['token'],
                'deviceid' => $gateway->data['deviceid'],
                'sim'      => $gateway->data['sim'],
            ];
        } elseif ($gateway->driver == 'ghasedak') {
            $config = [
                'url'    => 'http://api.iransmsservice.com',
                'apiKey' => $gateway->data['apiKey'],
                'from'   => $gateway->data['from'],
            ];
        } elseif ($gateway->driver == 'sabapayamak') {
            $config = [
                'url'             => 'https://api.SabaPayamak.com',
                'username'        => $gateway->data['username'],
                'password'        => $gateway->data['password'],
                'from'            => $gateway->data['from'],
                'token_valid_day' => 30,
            ];
        } elseif ($gateway->driver == 'bulksmsbd') {
            $config = [
                'url'      => 'http://66.45.237.70/api.php',
                'username' => $gateway->data['username'],
                'password' => $gateway->data['password'],
            ];
        } elseif ($gateway->driver == 'nexmo') {
            $config = [
                'key'    => $gateway->data['key'],
                'secret' => $gateway->data['secret'],
            ];
        } elseif ($gateway->driver == 'infobip') {
            $config = [
                'username' => $gateway->data['username'],
                'password' => $gateway->data['password'],
                'sender'   => $gateway->data['sender'],
            ];
        } elseif ($gateway->driver == 'messagebird') {
            $config = [
                'apiKey' => $gateway->data['apiKey'],
                'sender' => $gateway->data['sender'],
            ];
        } elseif ($gateway->driver == 'bulksmsnigeria') {
            $config = [
                'apiKey' => $gateway->data['apiKey'],
                'sender' => $gateway->data['sender'],
            ];
        } elseif ($gateway->driver == 'nigeriabulksms') {
            $config = [
                'username' => $gateway->data['username'],
                'password' => $gateway->data['password'],
                'sender'   => $gateway->data['sender'],
            ];
        } elseif ($gateway->driver == 'httpurl') {
            $config = [
                'url' => $gateway->data['url'],
            ];
        }

        config(["sms.drivers.{$gateway->driver}" => $config]);

    }

    /**
     * @param $phone
     * @param $message
     * @return mixed
     */
    public function send($phone, $message)
    {
        try {
            Sms::via($this->driver)->send($message, function ($sms) use ($phone) {
                $sms->to([$phone]);
            });
            $action = [
                'success' => true,
                'message' => 'SMS send successfully',
            ];
        } catch (Exception $e) {
            $action = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
        return $action;
    }
}
