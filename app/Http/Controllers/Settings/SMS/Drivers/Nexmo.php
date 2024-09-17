<?php

namespace App\Http\Controllers\Settings\SMS\Drivers;

use Exception;
use Tzsk\Sms\Contracts\Driver;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

class Nexmo extends Driver
{
    /**
     * @var mixed
     */
    protected $client;

    protected function boot(): void
    {
        $basic = new Basic(data_get($this->settings, 'key'), data_get($this->settings, 'secret'));
        $this->client = new Client($basic);
    }

    public function send()
    {
        try {
            foreach ($this->recipients as $recipient) {
                $receiverNumber = $recipient;
                $message = $this->body;
                $response = $this->client->sms()->send(
                    new SMS($receiverNumber, 'Vonage APIs', $message)
                );
                $message = $response->current();
            }
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}
