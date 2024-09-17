<?php
namespace App\Http\Controllers\Settings\Whatsapp\Drivers;

use Exception;
use Illuminate\Support\Facades\Http;

class SendchampDriver
{
    /**
     * @param $payload
     */
    public function send($payload)
    {
        $url = "https://api.sendchamp.com/api/v1/whatsapp/message/send";
        $params = [
            "recipient" => $payload['number'],
            "message"   => $payload['message'],
            "sender"    => $payload['sender'],
            "type"      => "text",
        ];
        $token = config("whatsapp.sendchamp.secret");

        $response = Http::acceptJson()->withToken($token)->post($url, $params);
        if ($response->failed()) {
            $response = $response->object();
            throw new Exception($response->message);
        }
    }
}
