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
        'name_en' => 'Test Product - ' . $faker->company . '-en',
        'name_zh' => '测试商品分类 - ' . $faker->company . '-zh',
        'description_en' => 'Test Product - ' . $faker->text(20) . '-en',
        'description_zh' => '测试商品分类 - ' . $faker->text(20) . '-zh',
        'content_en' => 'Test Product - ' . $faker->text(100) . '-en',
        'content_zh' => '测试商品分类 - ' . $faker->text(100) . '-zh',
        'thumb' => asset('demo/product-' . random_int(1, 46) . '.jpg'),
        'photos' => array(asset('demo/product-' . random_int(1, 46) . '.jpg'), asset('demo/product-' . random_int(1, 46) . '.jpg'), asset('demo/product-' . random_int(1, 46) . '.jpg')),
        'shipping_fee' => $faker->randomFloat(2, 0, 20),
        'stock' => $faker->randomNumber(5, true),
        'price' => $faker->randomFloat(2, 10, 20),
        'is_index' => true,
        'on_sale' => true,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
