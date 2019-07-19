<?php

namespace App\Jobs;

use App\Events\OrderCompletedEvent;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AutoCompleteOrderJob implements ShouldQueue
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
        // 判断对应的订单是否已经确认
        // 如果已经确认，则不需要确认订单，直接退出
        if ($this->order->status != Order::ORDER_STATUS_RECEIVING) {
            return;
        }

        event(new OrderCompletedEvent($this->order));
    }
}
