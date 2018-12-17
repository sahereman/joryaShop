<?php

namespace App\Listeners;

use App\Events\OrderRefundedWithShipmentEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderRefundedWithShipmentEventListener
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
     * @param  OrderRefundedWithShipmentEvent  $event
     * @return void
     */
    public function handle(OrderRefundedWithShipmentEvent $event)
    {
        $order = $event->getOrder();
        // 恢复 Product & Sku +库存
        foreach ($order->items as $item) {
            $item->sku->increment('stock', $item->number);
            $item->sku->product->increment('stock', $item->number);
        }
    }
}
