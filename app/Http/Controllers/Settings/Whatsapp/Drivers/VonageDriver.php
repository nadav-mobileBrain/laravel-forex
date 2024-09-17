<?php
namespace App\Http\Controllers\Settings\Whatsapp\Drivers;

use Exception;
use Illuminate\Support\Facades\Http;

class VonageDriver
{
    /**
     * @param $payload
     */
    public function send($payload)
    {
        if ($payload['env'] == 'sandbox') {
            $url = "https://messages-sandbox.nexmo.com/v0.1/messages";
        } else {
            $url = "https://api.nexmo.com/v0.1/messages";
        }
        $params = [
            "to"      => [
                "type"   => "whatsapp",
                "number" => $payload['number'],
            ],
            "from"    => [
                "type"   => "whatsapp",
                "number" => $payload['sender'],
            ],
            "message" => [
                "content" => [
                    "type" => "text",
                    "text" => $payload['message'],
                ],
            ],
        ];
        $response = Http::acceptJson()->withBasicAuth($payload['apiKey'], $payload['apiSecret'])->post($url, $params);
        // $headers = ["Authorization" => "Basic " . base64_encode($payload['apiKey'] . ":" . $payload['apiSecret'])];
        if ($response->failed()) {
            $response = $response->object();
            throw new Exception($response->title);
        }
    }
}
