<?php

namespace App\Http\Controllers\Settings\SMS\Drivers;

use Exception;
use Illuminate\Support\Facades\Http;
use Tzsk\Sms\Contracts\Driver;

class Infobip extends Driver
{

    protected function boot(): void
    {}

    public function send()
    {
        try {
            $message = urlencode($this->body);
            $data['user'] = data_get($this->settings, 'username');
            $data['password'] = data_get($this->settings, 'password');
            $data['sender'] = data_get($this->settings, 'sender');
            $data['SMSText'] = $message;
            $data['type'] = 'longSMS';
            foreach ($this->recipients as $recipient) {
                $data['GSM'] = $recipient;
                Http::get('https://api.infobip.com/api/v3/sendsms/plain', $data);
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}
