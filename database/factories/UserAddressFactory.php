<?php

use App\Models\CountryProvince;
use Faker\Generator as Faker;

$factory->define(App\Models\UserAddress::class, function (Faker $faker) {
    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth($now);
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    return [
        'name' => $faker->name,
        'phone' => $faker->phoneNumber,
        'country' => CountryProvince::where('type', 'country')->get()->random()->name_en,
        'province' => CountryProvince::where('type', 'province')->get()->random()->name_en,
        'city' => CountryProvince::where('type', 'city')->get()->random()->name_en,
        'address' => $faker->address,
        'is_default' => false,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
