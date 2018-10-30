<?php

namespace App\Listeners;

use App\Events\SmsResetEvent;
use App\Notifications\SmsResetNotification;
use Illuminate\Contracts\Queue\QueueableCollection;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use Illuminate\Queue\InteractsWithQueue;

class SmsResetEventListener implements ShouldQueue
{
    use Notifiable;

    protected $country_code = 86;
    protected $phone_number;

    protected $prefers_sms = true;

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
     * @param  SmsResetEvent $event
     * @return void
     */
    public function handle(SmsResetEvent $event)
    {
        $this->country_code = $event->getCountryCode();
        $this->phone_number = $event->getPhoneNumber();
        $this->notify(new SmsResetNotification());
    }

    public function getCountryCode()
    {
        return $this->country_code;
    }

    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    public function failed(SmsResetEvent $event, $exception)
    {
        //
    }

    /**
     * Get the notification routing information for the given driver.
     *
     * @param  string $driver
     * @return mixed
     */
    public function routeNotificationFor($driver)
    {
        if (method_exists($this, $method = 'routeNotificationFor' . Str::studly($driver))) {
            return $this->{$method}();
        }

        switch ($driver) {
            case 'mail':
                return $this->email;
            case 'sms':
                return [
                    'phone_number' => $this->phone_number,
                    'country_code' => $this->country_code,
                ];
            default:
                return [
                    'phone_number' => $this->phone_number,
                    'country_code' => $this->country_code,
                ];
        }
    }
}
