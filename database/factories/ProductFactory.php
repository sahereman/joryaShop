<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Product::class, function (Faker $faker) {
    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth($now);
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);


    return [
        'name_en' => $faker->company.'-en',
        'name_zh' => $faker->company.'-zh',
        'description_en' =>  $faker->text(20).'-en',
        'description_zh' =>  $faker->text(20).'-zh',
        'content_en' => $faker->text(100).'-en',
        'content_zh' => $faker->text(100).'-zh',
        'thumb' => $faker->imageUrl(),
        'photos' => json_encode(array($faker->imageUrl(), $faker->imageUrl(), $faker->imageUrl())),
        'shipping_fee' => $faker->randomFloat(2, 0, 20),
        'stock' => $faker->randomNumber(3),
        'price' => $faker->randomFloat(2, 10, 20),
        'is_index' => true,
        'on_sale' => true,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
