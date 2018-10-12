<?php

namespace App\Listeners;

use App\Events\EmailCodeResetEvent;
use App\Notifications\EmailCodeResetNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;

class EmailCodeResetEventListener
{
    use Notifiable;

    protected $email;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EmailCodeResetEvent  $event
     * @return void
     */
    public function handle(EmailCodeResetEvent $event)
    {
        $email = $event->getEmail();
        $this->email = $email;
        $this->notify(new EmailCodeResetNotification());
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function failed(EmailCodeResetEvent $event, $exception)
    {
        //
    }
}
