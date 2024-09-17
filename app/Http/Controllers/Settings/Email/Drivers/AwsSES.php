<?php

namespace App\Http\Controllers\Settings\Email\Drivers;

use App\Models\EmailDriver;

class AwsSES
{
    /**
     * @var mixed
     */
    protected $senderEmail;
    /**
     * @var mixed
     */
    protected $senderName;

    public function __construct()
    {
        $driver = EmailDriver::whereDriver('ses')->firstOrFail();
        config(['services.ses' => [
            'key'    => $driver->data['key'],
            'secret' => $driver->data['secret'],
            'region' => $driver->data['region'],
        ]]);
        $this->senderEmail = $driver->data['sender_email'];
        $this->senderName = $driver->data['sender_name'];
    }

    /**
     * @param $receiverEmail
     * @param $receiverName
     * @param $subject
     */
    public function send($receiverEmail, $receiverName, $subject = null)
    {

    }
}
