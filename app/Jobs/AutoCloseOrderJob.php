<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserCoupon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AutoCloseOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     * @param $order \App\Models\Order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    // 定义这个任务类具体的执行逻辑
    // 当队列处理器从队列中取出任务时，会调用 handle() 方法
    /**
     * Execute the job.
     * @return void
     * @throws \Throwable
     */
    public function handle()
    {
        // 判断对应的订单是否已经被支付
        // 如果已经支付，则不需要关闭订单，直接退出
        if ($this->order->paid_at != null && $this->order->status != Order::ORDER_STATUS_PAYING) {
            return;
        }
        // 通过事务执行 sql
        DB::transaction(function () {
            // 将订单的 status 字段标记为 closed，即关闭订单
            $this->order->update([
                'status' => Order::ORDER_STATUS_CLOSED,
                'closed_at' => Carbon::now()->toDateTimeString(),
            ]);

            // 恢复 Product & Sku +库存
            $this->order->items->each(function (OrderItem $item) {
                // 定制商品忽略库存变动
                if ($item->sku->product->type != Product::PRODUCT_TYPE_CUSTOM) {
                    // 更新 Sku +库存
                    // $item->sku->increment('stock', $item->number);

                    // 更新 Product +库存
                    // $item->sku->product->increment('stock', $item->number);

                    // flush product_sku_attr_value_cache
                    if (Cache::has($item->sku->product->id . 'product_sku_attr_value_cache')) {
                        Cache::forget($item->sku->product->id . 'product_sku_attr_value_cache');
                    }
                }
            });

            // 恢复 UserCoupon 记录
            $this->order->coupons->each(function (UserCoupon $coupon) {
                $coupon->update([
                    'order_id' => null,
                    'used_at' => null
                ]);
            });
        });
    }
}
