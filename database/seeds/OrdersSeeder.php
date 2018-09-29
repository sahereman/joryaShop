<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSku;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {

        // 创建 100 笔订单
        $user_ids = User::all()->pluck('id')->toArray();
        $orders = factory(Order::class, 100)->make();
        $skus = ProductSku::all();

        /*order*/
        $orders->transform(function ($order) use ($user_ids, $skus) {
            $order->user_id = array_random($user_ids);
            $order->currency = 'CNY';

            /*item*/
            $items = factory(OrderItem::class, random_int(2, 4))->create();
            $items->transform(function ($item) {


                return $item;
            });


            //交易成功,已评价
            $order->status = 'completed';


            return $order;
        });


        // Order::truncate();
        //        $users = User::all();
    }
}
