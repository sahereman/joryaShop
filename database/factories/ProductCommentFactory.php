<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ProductComment::class, function (Faker $faker) {
    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth($now);
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    return [
        // 'user_id' => $faker->randomDigit,
        // 'order_id' => $faker->randomNumber(2),
        // 'product_id' => $faker->randomDigit,
        'content' => $faker->text(50),
        'photos' => json_encode(array($faker->imageUrl(), $faker->imageUrl())),
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
