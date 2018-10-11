<?php

namespace App\Listeners;

use App\Events\EmailCodeLoginEvent;
use App\Notifications\EmailCodeLoginNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;

class EmailCodeLoginEventListener implements ShouldQueue
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
     * @param  EmailCodeLoginEvent $event
     * @return void
     */
    public function handle(EmailCodeLoginEvent $event)
    {
        $email = $event->getEmail();
        $this->email = $email;
        $this->notify(new EmailCodeLoginNotification());
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function failed(EmailCodeLoginEvent $event, $exception)
    {
        //
    }
}
