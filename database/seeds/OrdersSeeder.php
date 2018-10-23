<?php

use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductSku;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(Faker $faker)
    {
        // 创建 100 笔订单
        /*$user_ids = User::all()->pluck('id')->toArray();
        $orders = factory(Order::class, 100)->make();
        $skus = ProductSku::all();*/

        /*order*/
        //$orders->transform(function ($order) use ($user_ids, $skus) {
        /*$order->user_id = array_random($user_ids);
        $order->currency = 'CNY';*/

        /*item*/
        /*$items = factory(OrderItem::class, random_int(2, 4))->create();
        $items->transform(function ($item) {

            return $item;
        });*/

        //交易成功,已评价
        /*$order->status = 'completed';

        return $order;*/
        //});

        // 现在时间
        // $faker = new Faker();
        $now = \Carbon\Carbon::now()->toDateTimeString();
        $latest = $faker->dateTimeThisMonth($now);
        $latter = $faker->dateTimeThisMonth($latest);
        // 传参为生成最大时间不超过，创建时间永远比更改时间要早
        $former = $faker->dateTimeThisMonth($latter);
        $earlier = $faker->dateTimeThisMonth($former);
        factory(Order::class, 10)->create([
            'snapshot' => $this->makeRandomSnapshot(),
        ]);
        factory(Order::class, 10)->create([
            'status' => Order::ORDER_STATUS_CLOSED,
            'snapshot' => $this->makeRandomSnapshot(),
            'closed_at' => $earlier,
        ]);
        factory(Order::class, 10)->create([
            'status' => Order::ORDER_STATUS_SHIPPING,
            'snapshot' => $this->makeRandomSnapshot(),
            'payment_sn' => 'PAYMENT_SN_ALIPAY_88888888',
            'payment_method' => Order::PAYMENT_METHOD_ALIPAY,
            'paid_at' => $earlier,
        ]);
        factory(Order::class, 10)->create([
            'status' => Order::ORDER_STATUS_RECEIVING,
            'snapshot' => $this->makeRandomSnapshot(),
            'payment_sn' => 'PAYMENT_SN_ALIPAY_88888888',
            'payment_method' => Order::PAYMENT_METHOD_ALIPAY,
            'paid_at' => $earlier,
            'shipment_sn' => 'SHIPMENT_SN_88888888',
            'shipment_company' => 'SHIPMENT_COMPANY_AAAAAAAA',
            'shipped_at' => $former,
        ]);
        factory(Order::class, 10)->create([
            'status' => Order::ORDER_STATUS_COMPLETED,
            'snapshot' => $this->makeRandomSnapshot(),
            'payment_sn' => 'PAYMENT_SN_ALIPAY_88888888',
            'payment_method' => Order::PAYMENT_METHOD_ALIPAY,
            'paid_at' => $earlier,
            'shipment_sn' => 'SHIPMENT_SN_88888888',
            'shipment_company' => 'SHIPMENT_COMPANY_AAAAAAAA',
            'shipped_at' => $former,
            'completed_at' => $latter,
        ]);
        factory(Order::class, 10)->create([
            'status' => Order::ORDER_STATUS_COMPLETED,
            'snapshot' => $this->makeRandomSnapshot(),
            'payment_sn' => 'PAYMENT_SN_ALIPAY_88888888',
            'payment_method' => Order::PAYMENT_METHOD_ALIPAY,
            'paid_at' => $earlier,
            'shipment_sn' => 'SHIPMENT_SN_88888888',
            'shipment_company' => 'SHIPMENT_COMPANY_AAAAAAAA',
            'shipped_at' => $former,
            'completed_at' => $latter,
            'commented_at' => $latest,
        ]);
    }

    protected function makeRandomSnapshot()
    {
        $sku_count = ProductSku::all()->count();
        $random_count = random_int(3, 5);
        $random_snapshot = [];
        for ($i = 0; $i < $random_count; $i++) {
            $random_sku = ProductSku::find(random_int(1, $sku_count));
            $random_snapshot[$i]['sku_id'] = $random_sku->id;
            $random_snapshot[$i]['price'] = $random_sku->price;
            $random_snapshot[$i]['number'] = random_int(1, 5);
        }
        return $random_snapshot;
    }
}
