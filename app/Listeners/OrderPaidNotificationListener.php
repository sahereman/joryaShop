<?php

namespace App\Listeners;

use App\Events\OrderPaidEvent;
use App\Notifications\OrderUserPaidNotification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPaidNotificationListener
{
    public function __construct()
    {
        //
    }

    public function handle(OrderPaidEvent $event)
    {
        $order = $event->getOrder();

        if ($order->user) {
            $order->user->notify(new OrderUserPaidNotification($order));
        }
    }
}
