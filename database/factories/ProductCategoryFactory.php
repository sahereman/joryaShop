<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ProductCategory::class, function (Faker $faker) {


    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth($now);
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);

    return [
        'name_en' => 'Test Product Category - ' . $faker->name . '-en',
        'name_zh' => '测试商品分类 - ' . $faker->name . '-zh',
        'description_en' => 'Your Fashion, We care',
        'description_zh' => '莱瑞美业 真我风采',
        // 'banner' => $faker->imageUrl(), // 备用字段
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
