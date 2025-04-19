<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SendConfirmationEmail extends Mailable
{
    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('emails.confirmation')
                    ->with([
                        'name' => $this->user->name,
                    ]);
    }
}
