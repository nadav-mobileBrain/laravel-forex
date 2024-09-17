<?php
namespace App\Http\Controllers\Settings\Whatsapp\Drivers;

use Exception;
use Illuminate\Support\Facades\Http;

class ChatApiDriver
{
    /**
     * @param $payload
     */
    public function send($payload)
    {
        $url = "http://chat-api.phphive.info/message/send/text";
        $params = ["jid" => $payload['number'] . "@s.whatsapp.net", "message" => $payload['message']];
        $token = config("whatsapp.chatapi.secret");

        $response = Http::acceptJson()->withToken($token)->post($url, $params);
        if ($response->failed()) {
            $response = $response->object();
            throw new Exception($response->message);
        }
    }
}
