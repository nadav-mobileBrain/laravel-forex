<?php

namespace App\Http\Controllers\Settings\SMS\Drivers;

use Exception;
use Illuminate\Support\Facades\Http;
use Tzsk\Sms\Contracts\Driver;

class Nigeriabulksms extends Driver
{

    protected function boot(): void
    {}

    public function send()
    {
        try {
            $message = urlencode($this->body);
            $data['username'] = data_get($this->settings, 'username');
            $data['password'] = data_get($this->settings, 'password');
            $data['sender'] = data_get($this->settings, 'sender');
            $data['message'] = urlencode($message);
            $data['mobiles'] = getCommaSeparatedNumbers($this->recipients);
            $response = Http::get('http://portal.nigeriabulksms.com/api', $data);
            if ($response->successful()) {
                $response = $response->object();
                if (!property_exists($response, 'status')) {
                    throw new Exception($response->error);
                }
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}
