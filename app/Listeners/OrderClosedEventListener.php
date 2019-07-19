<?php

namespace App\Listeners;

use App\Events\OrderClosedEvent;
use App\Models\Order;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
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
                $item->sku->increment('stock', $item->number);
                $item->sku->product->increment('stock', $item->number);
                $item->sku->product->decrement('index', $item->number);
                $item->sku->product->decrement('heat');
            }
        });
    }
}
