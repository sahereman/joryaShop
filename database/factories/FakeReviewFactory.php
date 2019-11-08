<?php

use Faker\Generator as Faker;

$factory->define(App\Models\FakeReview::class, function (Faker $faker) {
    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth($now);
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    return [
        'photo' => $faker->imageUrl(640, 480, null, false), // Note: $faker->image() will download an image file into /tmp/ locally.
        'review' => $faker->sentence(100),
        'name' => $faker->name('male'),
        'reviewed_at' => $faker->dateTime('+10 days'),
        'sort' => $faker->randomDigit,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
