<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AutoCloseOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, $ttl)
    {
        $this->order = $order;
        // 设置延迟的时间，delay() 方法的参数代表多少秒之后执行
        $this->delay($ttl);
    }

    // 定义这个任务类具体的执行逻辑
    // 当队列处理器从队列中取出任务时，会调用 handle() 方法
    /**
     * @throws \Throwable
     */
    public function handle()
    {
        // 判断对应的订单是否已经被支付
        // 如果已经支付则不需要关闭订单，直接退出
        if ($this->order->paid_at) {
            return;
        }
        // 通过事务执行 sql
        DB::transaction(function () {
            // 将订单的 status 字段标记为 closed，即关闭订单
            $this->order->update([
                'status' => 'closed',
                'close_at' => Carbon::now()->toDateTimeString(),
            ]);
            // 恢复 Product & Sku +库存 & -销量
            foreach ($this->order->items as $item) {
                $item->sku->increment('stock', $item->number);
                $item->sku->decrement('sales', $item->number);
                $item->sku->product->increment('stock', $item->number);
                $item->sku->product->decrement('sales', $item->number);
            }
        });
    }
}
