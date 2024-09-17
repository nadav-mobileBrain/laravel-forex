<?php

namespace App\Http\Controllers\Settings\SMS\Drivers;

use Exception;
use Tzsk\Sms\Contracts\Driver;

class BulksmsBD extends Driver
{
    /**
     * @var mixed
     */

    protected function boot(): void
    {}

    public function send()
    {
        $numbers = getCommaSeparatedNumbers($this->recipients);
        $text = $this->body;
        $url = data_get($this->settings, 'url');
        $data = [
            'username' => data_get($this->settings, 'username'),
            'password' => data_get($this->settings, 'password'),
            'number'   => "$numbers",
            'message'  => "$text",
        ];

        $ch = curl_init(); // Initialize cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $p = explode("|", $result);
        $status = $p[0];
        if ($status != 1101) {
            throw new Exception('Something wrong this gateway.');
        }
    }
}
