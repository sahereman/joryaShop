<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Menu::class, function (Faker $faker) {
    return [
        'parent_id' => 0,
        'icon' => $faker->imageUrl(),
        'sort' => $faker->randomNumber(3, true),
    ];
});
