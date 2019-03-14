<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Feedback::class, function (Faker $faker) {
    return [
        // By now, email is required only.
        'user_id' => 1,
        'name' => 'aaa',
        'gender' => 'male',
        'phone' => '12312345678',
        'email' => $faker->unique()->safeEmail,
        'type' => 'subscription',
        'content' => 'Subscribe to get product information, maintenance knowledge, special offers and important notices.',
        'is_check' => 0,
    ];
});
