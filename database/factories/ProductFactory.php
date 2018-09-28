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
        'product_category_id' => random_int(2,10),
        'name_en' => $faker->name,
        'name_zh' => $faker->name,
        'description_en' => $faker->sentence(10),
        'description_zh' => $faker->sentence(10),
        'content_en' => $faker->text(100),
        'content_zh' => $faker->text(100),
        'photos' => json_encode(array($faker->imageUrl(), $faker->imageUrl(), $faker->imageUrl())),
        'shipping_fee' => $faker->randomFloat(2, 10, 20),
        'stock' => $faker->randomNumber(5),
        'on_sale' => true,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
