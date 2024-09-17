<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\EmailDriver;
use App\Models\EmailSetting;
use App\Http\Controllers\Settings\Email\EmailGlobalController;

class PaymentConfirm
{
    /**
     * @var mixed
     */
    public $userId;
    /**
     * @var mixed
     */
    public $usd;
    /**
     * @var mixed
     */
    public $custom;
    /**
     * @var mixed
     */
    public $gateway;
    /**
     * @var mixed
     */
    public $currency;
    /**
     * @param int $userId
     * @param float $usd
     * @param string $custom
     * @param string $gateway
     */
    public function __construct($userId, $usd, $custom, $gateway, $currency)
    {
        $this->userId = $userId;
        $this->usd = $usd;
        $this->custom = $custom;
        $this->gateway = $gateway;
        $this->currency = $currency;
    }

    public function sendMessage()
    {
        $driver = EmailDriver::whereStatus(true)->inRandomOrder()->first();
        $emailSetting = EmailSetting::first();
        $user = User::findOrFail($this->userId);

        $payload['email'] = $user->email;
        $payload['name'] = $user->name;
        $payload['subject'] = "Plan Subscribe purchase Completed";

        $urText = 'Your Plan Payment Received Successfully.<br>We Received ' . number_format($this->usd, 2) . ' ' . $this->currency . ' via - ' . $this->gateway . ' <br> Order Number is : ' . strtoupper($this->custom) . '<br>';
        $body = $emailSetting->email_body;
        $body = str_replace("{{name}}", $user->name, $body);
        $body = str_replace("{{message}}", $urText, $body);
        $body = str_replace("{{site_title}}", $driver->data['sender_name'], $body);

        $payload['view'] = 'emails.email';
        $payload['viewData'] = [
            'name' => $user->name,
            'body' => $body,
        ];

        (new EmailGlobalController($driver))->send($payload);
    }
}
