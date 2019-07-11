<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Coupon::class, function (Faker $faker) {
    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();
    $stopped_at = \Carbon\Carbon::now()->addMonth(1)->toDateTimeString();
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth($now);
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    return [
        'name' => 'Test Coupon',
        'type' => 'discount',
        'threshold' => 1000,
        'number' => null,
        'allowance' => 1,
        'scenario' => 'register',
        'sort' => 0,
        'started_at' => $now,
        'stopped_at' => $stopped_at,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
