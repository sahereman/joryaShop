<?php

namespace App\Listeners;

use App\Events\OrderClosedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderClosedEventListener
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
     * @param  OrderClosedEvent  $event
     * @return void
     */
    public function handle(OrderClosedEvent $event)
    {
        $order = $event->getOrder();
        // 恢复 Product & Sku +库存 -人气|热度 -综合指数
        foreach ($order->items as $item) {
            $item->sku->increment('stock', $item->number);
            $item->sku->product->increment('stock', $item->number);
            $item->sku->product->decrement('index', $item->number);
            $item->sku->product->decrement('heat');
        }
    }
}
