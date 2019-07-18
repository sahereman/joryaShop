<?php

namespace App\Listeners;

use App\Events\OrderCompletedEvent;
use App\Models\Order;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
     * @param OrderCompletedEvent $event
     * @throws \Exception
     * @throws \Throwable
     */
    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->getOrder();

        // 通过事务执行 sql
        DB::transaction(function () use ($order) {
            // 将订单的 status 字段标记为 completed，即确认订单
            $order->update([
                'status' => Order::ORDER_STATUS_COMPLETED,
                'completed_at' => Carbon::now()->toDateTimeString(),
            ]);

            // Product & Sku +销量
            foreach ($order->items as $item) {
                $item->sku->increment('sales', $item->number);
                $item->sku->product->increment('sales', $item->number);
            }
        });



    }
}
