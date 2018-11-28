<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderRefund;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AutoDeclineOrderRefundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     * @param $order \App\Models\Order
     * @param $time_to_decline_order_refund integer unit:seconds
     * @return void
     */
    public function __construct(Order $order, $time_to_decline_order_refund)
    {
        $this->order = $order;
        // 设置延迟的时间，delay() 方法的参数代表多少秒之后执行
        $this->delay($time_to_decline_order_refund);
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
        // 判断对应的退单类型是否为refund[仅退款]
        // 如果是仅退款退单，则直接退出
        if ($this->order->refund->type == OrderRefund::ORDER_REFUND_TYPE_REFUND) {
            return;
        }

        // 判断对应的订单买家是否已经发货
        // 如果已经发货，则不需要拒绝售后申请，直接退出
        if ($this->order->refund->shipment_company != null && $this->order->refund->shipment_sn != null) {
            return;
        }

        // 通过事务执行 sql
        DB::transaction(function () {
            // 将订单的 status 字段标记为 completed，即确认订单
            $this->order->refund->update([
                'status' => OrderRefund::ORDER_REFUND_STATUS_DECLINED,
                'declined_at' => Carbon::now()->toDateTimeString(),
            ]);
        });
    }
}
