<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderRefund;

class OrderRefundsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Order::find([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])->each(function(Order $order){
            factory(OrderRefund::class)->create([
                'order_id' => $order->id,
            ]);
        });

        Order::find([21, 22, 23, 24, 25, 26, 27, 28, 29, 30])->each(function(Order $order){
            factory(OrderRefund::class)->create([
                'order_id' => $order->id,
            ]);
        });

        Order::find([31, 32, 33, 34, 35, 36, 37, 38, 39, 40])->each(function(Order $order){
            factory(OrderRefund::class)->create([
                'order_id' => $order->id,
                'type' => OrderRefund::ORDER_REFUND_TYPE_REFUND_WITH_SHIPMENT,
            ]);
        });*/

    }
}
