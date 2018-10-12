<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class EmailCodeResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $email = $notifiable->getEmail();
        $code = Str::random(6);
        $ttl = 10;
        Cache::set('reset_email_code-' . $email, $code, $ttl);
        // 60s内不允许重复发送邮箱验证码
        Cache::set('reset_email_code_sent-' . $email, true, 1);

        return (new MailMessage)
            ->subject('邮箱验证码')
            ->greeting('您好:')
            ->line('您本次用于重置密码的邮箱验证码为:')
            ->line($code)
            ->line('请于' . $ttl . '分钟内重置您的密码。')
            //->line('The introduction to the notification.')
            //->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
