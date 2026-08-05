<?php

namespace App\Notifications;

use App\Models\OtpCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class OtpCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public OtpCode $otp) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $purpose = $this->otp->type === OtpCode::TYPE_PASSWORD_RESET
            ? 'reset your password'
            : 'verify your email address';

        return (new MailMessage)
            ->subject('Your AmaSports verification code')
            ->greeting('Hi '.$notifiable->name.',')
            ->line("Use the code below to {$purpose}.")
            ->line(new HtmlString(
                '<strong style="font-size:24px;letter-spacing:4px;">'.$this->otp->code.'</strong>'
            ))
            ->line('This code expires in '.OtpCode::EXPIRY_MINUTES.' minutes.')
            ->line('If you did not request this, you can safely ignore this email.');
    }
}
