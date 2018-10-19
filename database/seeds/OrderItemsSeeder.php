<?php

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSku;

class OrderItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::where('status', '<>', Order::ORDER_STATUS_CLOSED)->get();
        $orders->each(function ($order){
            OrderItem::create([
                'order_id' => $order->id,
                'product_sku_id' => 1,
                'price' => 5.00,
                'number' => random_int(1, 5),
            ]);
        });
    }
}
