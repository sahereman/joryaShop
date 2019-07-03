<?php

namespace App\Listeners;

use App\Events\OrderPaidEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPaidNotification
{
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
     * @param  OrderPaidEvent  $event
     * @return void
     */
    public function handle(OrderPaidEvent $event)
    {
        //
    }
}
