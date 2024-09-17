<?php

namespace App\Http\Controllers\Settings\SMS\Drivers;

use Exception;
use Illuminate\Support\Facades\Http;
use Tzsk\Sms\Contracts\Driver;

class Bulksmsnigeria extends Driver
{

    protected function boot(): void
    {}

    public function send()
    {
        try {
            $payload['api_token'] = data_get($this->settings, 'apiKey');
            $payload['from'] = data_get($this->settings, 'sender');
            $payload['to'] = $this->recipients;
            $payload['body'] = urlencode($this->body);
            $response = Http::get('https://www.bulksmsnigeria.com/api/v1/sms/create', $payload);
            if ($response->failed()) {
                $response = $response->object();
                throw new Exception($response->error->message);
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}
