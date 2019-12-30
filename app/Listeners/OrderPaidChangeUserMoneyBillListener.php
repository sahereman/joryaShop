<?php

namespace App\Listeners;

use App\Events\OrderPaidEvent;
use App\Models\Order;
use App\Models\User;
use App\Models\UserMoneyBill;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderPaidChangeUserMoneyBillListener
{
    public function __construct()
    {
        //
    }

    public function handle(OrderPaidEvent $event)
    {
        $order = $event->getOrder();

        $umBill = new UserMoneyBill();
        if($order->user instanceof User)
        {
            $umBill->change($order->user,
                $umBill::TYPE_ORDER_PAYMENT,
                $order->currency,
                $order->total_amount,
                $order);
        }
    }
}
