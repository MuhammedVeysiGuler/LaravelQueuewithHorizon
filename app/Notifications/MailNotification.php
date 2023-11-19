<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MailNotification extends Notification
{
    use Queueable;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }


    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->view('mailTemplate.notification', ['notificationMessage' => $this->message])
            ->subject('Duyuru !!');
    }


    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
