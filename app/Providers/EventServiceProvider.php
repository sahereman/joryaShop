<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /*'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],*/
        'App\Events\EmailCodeLoginEvent' => [
            'App\Listeners\EmailCodeLoginEventListener',
        ],
        'App\Events\EmailCodeResetEvent' => [
            'App\Listeners\EmailCodeResetEventListener',
        ],
        'App\Events\EmailCodeRegisterEvent' => [
            'App\Listeners\EmailCodeRegisterEventListener',
        ],
        'App\Events\SmsLoginEvent' => [
            'App\Listeners\SmsLoginEventListener',
        ],
        'App\Events\SmsResetEvent' => [
            'App\Listeners\SmsResetEventListener',
        ],
        'App\Events\SmsRegisterEvent' => [
            'App\Listeners\SmsRegisterEventListener',
        ],
        'App\Events\OrderSnapshotEvent' => [
            'App\Listeners\OrderSnapshotEventListener',
        ],
        /*'Illuminate\Auth\Events\Authenticated' => [
            'App\Listeners\LogAuthenticatedListener'
        ],*/
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
