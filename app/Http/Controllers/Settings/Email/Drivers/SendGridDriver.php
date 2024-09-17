<?php

namespace App\Http\Controllers\Settings\Email\Drivers;

use SendGrid;
use Exception;
use SendGrid\Mail\To;
use SendGrid\Mail\From;
use SendGrid\Mail\Mail;
use SendGrid\Mail\Subject;
use SendGrid\Mail\HtmlContent;

class SendGridDriver
{

    /**
     * @param $payload
     */
    public function send($payload)
    {
        $from = new From($payload['senderEmail']);
        $to = new To($payload['email'], $payload['name']);
        $subject = new Subject($payload['subject']);
        $html = view($payload['view'], $payload['viewData'])->render();
        $htmlContent = new HtmlContent($html);

        $email = new Mail(
            $from,
            $to,
            $subject,
            null,
            $htmlContent
        );

        $sendgrid = new SendGrid($payload['apiKey']);
        $response = $sendgrid->send($email);

        if ($response->statusCode() != 202) {
            $response = json_decode($response->body())->errors[0];
            throw new Exception("Error: " . $response->message);
        }
    }
}
