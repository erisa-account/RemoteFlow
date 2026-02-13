<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class CheckinMail extends Mailable
{
    public $userName;

    public function __construct($userName)
    {
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->subject('Missing Check-in')
                    ->markdown('emails.checkinemail');
    }
}