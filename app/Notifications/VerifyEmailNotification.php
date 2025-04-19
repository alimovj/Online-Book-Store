<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;

class VerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Emailingizni tasdiqlang')
            ->line('Tizimga to‘liq kirish uchun emailingizni tasdiqlang.')
            ->action('Emailni tasdiqlash', $this->verificationUrl($notifiable));
    }
}
