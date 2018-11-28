<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

// class EmailVerificationCodeNotification extends Notification implements ShouldQueue
class EmailVerificationCodeNotification extends Notification
{
    // use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // $email = $notifiable->getEmail();
        $code = $notifiable->getCode();
        $ttl = $notifiable->getTtl();
        $mailMessage = new MailMessage();
        if (App::isLocale('en')) {
            return $mailMessage->subject('Email Verification Code')
                ->greeting('Dear Customer:')
                ->line('Your Email Verification Code is:')
                ->line($code)
                ->line('Note: This verification code will be expired in ' . $ttl . 'minutes.')
                ->line('-- From: Jorya Hair --');
        } else {
            return $mailMessage->subject('邮箱验证码')
                ->greeting('您好:')
                ->line('您的邮箱验证码为:')
                ->line($code)
                ->line('该验证码将于' . $ttl . '分钟后失效。')
                ->line('-- 来自：卓雅美业 --');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
