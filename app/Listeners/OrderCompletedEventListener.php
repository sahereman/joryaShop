<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderCompletedEventListener
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
     * @param  OrderCompletedEvent  $event
     * @return void
     */
    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->getOrder();
        // Product & Sku +销量
        foreach ($order->items as $item) {
            $item->sku->increment('sales', $item->number);
            $item->sku->product->increment('sales', $item->number);
        }
    }
}
