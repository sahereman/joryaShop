<?php

namespace App\Listeners;

use App\Events\OrderShippedEvent;
use App\Notifications\OrderSellerShippedNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderShippedNotificationListener
{
    public function __construct()
    {
        //
    }

    public function handle(OrderShippedEvent $event)
    {
        $order = $event->getOrder();

        $order->user->notify(new OrderSellerShippedNotification($order));
    }
}
