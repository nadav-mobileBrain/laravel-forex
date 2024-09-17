<?php
namespace App\Http\Controllers\Settings\Whatsapp\Drivers;

use Http;
use Exception;

class UltramsgDriver
{
    /**
     * @param $payload
     */
    public function send($payload)
    {

        $token = config("whatsapp.ultramsg.token"); // Ultramsg.com token
        $instance_id = config("whatsapp.ultramsg.instance"); // Ultramsg.com instance id

        $params = [
            'token'       => $token,
            'to'          => $payload['number'],
            'body'        => $payload['message'],
            'priority'    => '10',
            'referenceId' => '',
        ];

        $response = Http::asForm()->post("https://api.ultramsg.com/{$instance_id}/messages/chat", $params);
        if ($response->failed()) {
            $response = $response->object();
            throw new Exception($response->error);
        }
    }
}
