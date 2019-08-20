<?php

use Faker\Generator as Faker;

$factory->define(App\Models\CustomAttr::class, function (Faker $faker) {
    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth($now);
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    return [
        'type' => 'BASE',
        'is_required' => true,
        'photo' => $faker->imageUrl(640, 480, null, false), // Note: $faker->image() will download an image file into /tmp/ locally.
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
