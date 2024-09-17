<?php
namespace App\Http\Controllers\Settings\Whatsapp\Drivers;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Http;

class TwilioDriver
{
    /**
     * @param $payload
     */
    public function send($payload)
    {
        $sid = config("whatsapp.twilio.sid");
        $token = config("whatsapp.twilio.token");
        $wa_from = config("whatsapp.twilio.from");

        $twilio = new Client($sid, $token);

        $body = $payload['message'];
        $recipient = $payload['number'];
        $twilio->messages->create("whatsapp:$recipient", ["from" => "whatsapp:$wa_from", "body" => $body]);
    }
}
