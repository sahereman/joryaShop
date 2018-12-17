<?php

namespace App\Listeners;

use App\Events\OrderRefundingEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderRefundingEventListener
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
     * @param  OrderRefundingEvent  $event
     * @return void
     */
    public function handle(OrderRefundingEvent $event)
    {
        $order = $event->getOrder();
        // 恢复 Product -人气|热度 -综合指数
        foreach ($order->items as $item) {
            $item->sku->product->decrement('index', $item->number);
            $item->sku->product->decrement('heat');
        }
    }
}
