<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminCustomNotification extends Notification
{
    use Queueable;

    public $custom;

    public function __construct($custom_array)
    {
        $this->custom = $custom_array;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        // 存入数据库里的数据
        return [
            'title' => $this->custom['title'],
            'link' => $this->custom['link'],
        ];
    }
}
