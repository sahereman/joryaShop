<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Storage;

$factory->define(App\Models\Poster::class, function (Faker $faker) {
    // 现在时间
    $now = \Carbon\Carbon::now()->toDateTimeString();
    // 随机取一个月以内的时间
    $updated_at = $faker->dateTimeThisMonth($now);
    // 传参为生成最大时间不超过，创建时间永远比更改时间要早
    $created_at = $faker->dateTimeThisMonth($updated_at);
    // $prefix_path = Storage::disk('public')->getAdapter()->getPathPrefix();
    return [
        'name' => $faker->name,
        'slug' => $faker->slug,
        'disk' => 'public',
        // 'image' => $faker->image($prefix_path, 640, 480, null, false), // Note: $faker->image() will download an image file into /tmp/ locally.
        // 'image' => $faker->imageUrl(640, 480, null, false), // Note: $faker->image() will download an image file into /tmp/ locally.
        'photos' => array(asset('demo/product-' . random_int(1, 46) . '.jpg'), asset('demo/product-' . random_int(1, 46) . '.jpg'), asset('demo/product-' . random_int(1, 46) . '.jpg')),
        'link' => $faker->url,
        'created_at' => $created_at,
        'updated_at' => $updated_at,
    ];
});
