<?php

namespace App\Listeners;

use App\Events\EmailCodeRegisterEvent;
use App\Notifications\EmailCodeRegisterNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;

class EmailCodeRegisterEventListener implements ShouldQueue
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
     * @param  EmailCodeRegisterEvent $event
     * @return void
     */
    public function handle(EmailCodeRegisterEvent $event)
    {
        $email = $event->getEmail();
        $this->email = $email;
        $this->notify(new EmailCodeRegisterNotification());
    }
    
    public function getEmail()
    {
        return $this->email;
    }

    public function failed(EmailCodeRegisterEvent $event, $exception)
    {
        //
    }
}
