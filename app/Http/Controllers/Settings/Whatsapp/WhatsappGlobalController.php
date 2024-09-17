<?php

namespace App\Http\Controllers\Settings\Whatsapp;

use Exception;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Settings\Whatsapp\Drivers\TwilioDriver;
use App\Http\Controllers\Settings\Whatsapp\Drivers\VonageDriver;
use App\Http\Controllers\Settings\Whatsapp\Drivers\ChatApiDriver;
use App\Http\Controllers\Settings\Whatsapp\Drivers\UltramsgDriver;
use App\Http\Controllers\Settings\Whatsapp\Drivers\SendchampDriver;

class WhatsappGlobalController extends Controller
{
    /**
     * @var mixed
     */
    protected $driver;

    /**
     * @var mixed
     */
    protected $senderNumber;

    /**
     * @param $driver
     */
    public function __construct($driver)
    {
        $this->driver = $driver->driver;
        $this->senderNumber = $driver->data['sender_number'];
        if ($driver->driver == 'vonage') {
            $config = [
                'apiKey'    => $driver->data['apiKey'],
                'apiSecret' => $driver->data['apiSecret'],
                'env'       => $driver->data['env'],
            ];
        } elseif ($driver->driver == 'twilio') {
            $config = [
                'sid'   => $driver->data['sid'],
                'token' => $driver->data['token'],
                'from'  => $driver->data['sender_number'],
            ];
        } elseif ($driver->driver == 'sendchamp') {
            $config = [
                'secret' => $driver->data['secret'],
            ];
        } elseif ($driver->driver == 'chatapi') {
            $config = [
                'token' => $driver->data['token'],
            ];
        } elseif ($driver->driver == 'ultramsg') {
            $config = [
                'token'    => $driver->data['token'],
                'instance' => $driver->data['instance'],
            ];
        }

        config(["whatsapp.{$driver->driver}" => $config]);
    }

    /**
     * @param $payload
     */
    public function send($payload)
    {
        $payload['sender'] = $this->senderNumber;
        try {
            if ($this->driver == 'vonage') {
                $payload['apiKey'] = config('whatsapp.vonage.apiKey');
                $payload['apiSecret'] = config('whatsapp.vonage.apiSecret');
                $payload['env'] = config('whatsapp.vonage.env');
                (new VonageDriver())->send($payload);
            } elseif ($this->driver == 'twilio') {
                $rr = (new TwilioDriver())->send($payload);
                dd($rr);
            } elseif ($this->driver == 'sendchamp') {
                (new SendchampDriver())->send($payload);
            } elseif ($this->driver == 'chatapi') {
                (new ChatApiDriver())->send($payload);
            } elseif ($this->driver == 'ultramsg') {
                (new UltramsgDriver())->send($payload);
            }

            $action = ['success' => true];
        } catch (Exception $e) {
            $message = trim(preg_replace('/\s+/', ' ', $e->getMessage()));
            $action = ['success' => false, 'message' => $message];
        }
        return $action;
    }
}
