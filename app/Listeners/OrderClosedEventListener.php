<?php

namespace App\Listeners;

use App\Events\OrderClosedEvent;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
     * @param OrderClosedEvent $event
     * @throws \Exception
     * @throws \Throwable
     */
    public function handle(OrderClosedEvent $event)
    {
        $order = $event->getOrder();

        // 通过事务执行 sql
        DB::transaction(function () use ($order) {
            // 将订单的 status 字段标记为 closed，即关闭订单
            $order->update([
                'status' => Order::ORDER_STATUS_CLOSED,
                'close_at' => Carbon::now()->toDateTimeString(),
            ]);

            // 恢复 Product & Sku +库存 -人气|热度 -综合指数
            foreach ($order->items as $item) {
                // 定制商品忽略库存变动
                if ($item->sku->product->type != Product::PRODUCT_TYPE_CUSTOM) {
                    // 更新 Sku +库存
                    // $item->sku->increment('stock', $item->number);

                    // 更新 Product +库存
                    // $item->sku->product->increment('stock', $item->number);
                }
                $item->sku->product->decrement('index', $item->number);
                $item->sku->product->decrement('heat');

                // flush product_sku_attr_value_cache
                if (Cache::has($item->sku->product->id . 'product_sku_attr_value_cache')) {
                    Cache::forget($item->sku->product->id . 'product_sku_attr_value_cache');
                }
            }
        });
    }
}
