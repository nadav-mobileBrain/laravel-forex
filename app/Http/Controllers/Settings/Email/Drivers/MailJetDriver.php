<?php

namespace App\Http\Controllers\Settings\Email\Drivers;

use Exception;
use Mailjet\Client;
use Mailjet\Resources;

class MailJetDriver
{
    /**
     * @param $payload
     */
    public function send($payload)
    {
        $mailJet = new Client($payload['publicKey'], $payload['privateKey'], true, ['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From'     => [
                        'Email' => $payload['senderEmail'],
                        'Name'  => $payload['senderName'],
                    ],
                    'To'       => [
                        [
                            'Email' => $payload['email'],
                            'Name'  => $payload['name'],
                        ],
                    ],
                    'Subject'  => $payload['subject'],
                    'HTMLPart' => view($payload['view'], $payload['viewData'])->render(),
                ],
            ],
        ];
        $response = $mailJet->post(Resources::$Email, ['body' => $body]);
        if (!$response->success()) {
            $response = $response->getData();
            throw new Exception($response['ErrorMessage']);
        }
    }
}
