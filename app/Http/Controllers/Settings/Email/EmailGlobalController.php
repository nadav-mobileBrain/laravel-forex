<?php

namespace App\Http\Controllers\Settings\Email;

use Exception;
use App\Mail\EmailDriverMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Settings\Email\Drivers\MailJetDriver;
use App\Http\Controllers\Settings\Email\Drivers\SendGridDriver;

class EmailGlobalController extends Controller
{
    /**
     * @var mixed
     */
    public $driver;
    /**
     * @var mixed
     */
    protected $senderEmail;
    /**
     * @var mixed
     */
    protected $senderName;

    /**
     * @param $driver
     */
    public function __construct($driver)
    {
        $this->driver = $driver->driver;
        if ($driver->driver == 'ses') {
            $key = "services.{$driver->driver}";
            $config = [
                'key'    => $driver->data['key'],
                'secret' => $driver->data['secret'],
                'region' => $driver->data['region'],
            ];
        } elseif ($driver->driver == 'smtp') {
            $key = "mail.mailers.smtp";
            $config = [
                'transport'  => 'smtp',
                'host'       => $driver->data['host'],
                'port'       => $driver->data['port'],
                'username'   => $driver->data['username'],
                'password'   => $driver->data['password'],
                'encryption' => $driver->data['encryption'],
                'timeout'    => null,
            ];
        } elseif ($driver->driver == 'sendgrid') {
            $key = "sendgrid";
            $config = [
                'apiKey' => $driver->data['apiKey'],
            ];
        } elseif ($driver->driver == 'mailgun') {
            $key = "services.mailgun";
            $config = [
                'domain'   => $driver->data['domain'],
                'secret'   => $driver->data['secret'],
                'endpoint' => 'api.mailgun.net',
            ];
        } elseif ($driver->driver == 'postmark') {
            $key = "services.postmark";
            $config = [
                'token' => $driver->data['token'],
            ];
        } elseif ($driver->driver == 'mailjet') {
            $key = "mailjet";
            $config = [
                'publicKey'  => $driver->data['publicKey'],
                'privateKey' => $driver->data['privateKey'],
            ];
        } elseif ($driver->driver == 'sendmail') {
            $key = "mail.mailers.sendmail";
            $config = [
                'transport' => 'sendmail',
                'path'      => $driver->data['path'], //default: '/usr/sbin/sendmail -t -i',
            ];
        }

        config(["mail.default" => $driver->mailer]);
        config([$key => $config]);

        $this->senderEmail = $driver->data['sender_email'];
        $this->senderName = $driver->data['sender_name'];
    }

    /**
     * @param $receiverEmail
     * @param $receiverName
     * @param $subject
     */
    public function send($payload)
    {
        $payload['senderEmail'] = $this->senderEmail;
        $payload['senderName'] = $this->senderName;
        try {
            if ($this->driver == 'sendgrid') {
                $payload['apiKey'] = config('sendgrid.apiKey');
                (new SendGridDriver())->send($payload);
            } elseif ($this->driver == 'mailjet') {
                $payload['publicKey'] = config('mailjet.publicKey');
                $payload['privateKey'] = config('mailjet.privateKey');
                (new MailJetDriver())->send($payload);
            } else {
                Mail::to($payload['email'], $payload['name'])->send(new EmailDriverMail($payload));
            }
            $action = ['success' => true];
        } catch (Exception $e) {
            $action = ['success' => false, 'message' => strip_tags($e->getMessage())];
        }
        return $action;
    }
}
