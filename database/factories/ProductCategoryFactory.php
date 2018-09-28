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
        'parent_id' => 1,
        'name_en' => $faker->word,
        'name_zh' => $faker->word,
        'description_en' => $faker->sentence(10),
        'description_zh' => $faker->sentence(10),
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
