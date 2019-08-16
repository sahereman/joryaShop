<?php

namespace App\Listeners;

use App\Events\OrderRefundedWithShipmentEvent;
use App\Models\Product;
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
            // 定制商品忽略库存变动
            if ($item->sku->product->type != Product::PRODUCT_TYPE_CUSTOM) {
                // 更新 Sku +库存
                $item->sku->increment('stock', $item->number);

                // 更新 Product +库存
                $item->sku->product->increment('stock', $item->number);
            }
        }
    }
}
