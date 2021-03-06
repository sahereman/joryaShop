<?php

use App\Models\ExchangeRate;
use Faker\Generator as Faker;

$factory->define(App\Models\Order::class, function (Faker $faker) {
    // 随机取一个周以内的时间
    $updated_at = $faker->dateTimeBetween($startDate = '-6 days', $endDate = 'now');
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeBetween($startDate = '-6 days', $endDate = 'now');
    return [
        'order_sn' => \App\Models\Order::generateOrderSn(),
        'user_id' => 1,
        'user_info' => ["name"=>"aaa","phone"=>"18888888888","address"=>"somewhere"],
        'status' => \App\Models\Order::ORDER_STATUS_PAYING,
        'currency' => ExchangeRate::all()->random()->currency,
        'snapshot' => ["sku_id"=>1,"price"=>1.00,"number"=>1],
        'total_shipping_fee' => 1.00,
        'total_amount' => 1.00,
        'saved_fee' => $faker->randomFloat(2, 10, 20),
        'rate' => ExchangeRate::all()->random()->rate,
        'remark' => 'remark content ...',
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
