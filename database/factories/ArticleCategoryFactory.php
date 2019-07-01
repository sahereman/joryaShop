<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ArticleCategory::class, function (Faker $faker) {
    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth($now);
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    return [
        // 'banner' => $faker->imageUrl(), // 备用字段
        'name_en' => 'Test Product Category - ' . $faker->name ,
        'name_zh' => 'Test Product Category - ' . $faker->name ,
        'description_en' => 'Your Fashion, We Care',
        'description_zh' => 'Your Fashion, We Care -- zh',
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
