<?php

namespace App\Http\Controllers\Settings\SMS\Drivers;

use Exception;
use Illuminate\Support\Facades\Http;
use Tzsk\Sms\Contracts\Driver;

class HttpURL extends Driver
{

    protected function boot(): void
    {}

    public function send()
    {
        try {
            $url = data_get($this->settings, 'url');
            $text = urlencode($this->body);
            $url = str_replace("{{number}}", getCommaSeparatedNumbers($this->recipients), $url);
            $url = str_replace("{{message}}", $text, $url);
            $response = Http::get($url);
            if ($response->failed()) {
                throw new Exception('Something wrong there on url.');
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}
