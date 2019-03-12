<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Feedback::class, function (Faker $faker) {
    return [
        // By now, email is required only.
        'email' => $faker->unique()->safeEmail,
    ];
});
