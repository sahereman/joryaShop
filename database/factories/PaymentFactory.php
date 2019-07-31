<?php

use App\Models\ExchangeRate;
use App\Models\Payment;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {
    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth($now);
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    return [
        'sn' => Payment::generateSn(),
        'user_id' => 1,
        'currency' => ExchangeRate::all()->random()->currency,
        'amount' => $faker->randomFloat(2, 10, 20),
        'rate' => ExchangeRate::all()->random()->rate,
        'method' => Payment::PAYMENT_METHOD_PAYPAL,
        'payment_sn' => 'PAYPAL-' . Payment::generateSn(),
        'paid_at' => $updated_at,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
