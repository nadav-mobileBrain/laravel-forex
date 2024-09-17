<?php

namespace App\Http\Controllers\Settings\SMS\Drivers;

use Exception;
use MessageBird\Client;
use MessageBird\Objects\Message;
use Tzsk\Sms\Contracts\Driver;

class Messagebird extends Driver
{
    /**
     * @var mixed
     */

    protected $bird;

    protected function boot(): void
    {
        $this->bird = new Client(data_get($this->settings, 'apiKey'));
    }

    public function send()
    {
        try {
            $birdMessage = new Message();
            $birdMessage->originator = data_get($this->settings, 'sender');
            $birdMessage->recipients = $this->recipients;
            $birdMessage->body = $this->body;
            $this->bird->messages->create($birdMessage);
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }

}
