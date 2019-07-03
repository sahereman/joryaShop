<?php

namespace App\Listeners;

use App\Events\OrderShippedEvent;
use App\Notifications\OrderSellerShipped;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderShippedNotification
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
     * @param  OrderShippedEvent  $event
     * @return void
     */
    public function handle(OrderShippedEvent $event)
    {
        //
        $order = $event->getOrder();

        $order->user->notify(new OrderSellerShipped($order));
    }
}
