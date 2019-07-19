<?php

namespace App\Listeners;

use App\Events\OrderPaidEvent;
use App\Models\Order;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPaidEventListener
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
     * @param  OrderPaidEvent $event
     * @return void
     */
    public function handle(OrderPaidEvent $event)
    {
        $order = $event->getOrder();
        $order->update([
            'status' => Order::ORDER_STATUS_SHIPPING
        ]);
    }
}
