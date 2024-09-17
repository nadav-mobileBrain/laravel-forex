<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifySMTP extends Mailable {
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->subject('SMTP Validation Mail')
            ->view('emails.verify-smtp');
    }
}
