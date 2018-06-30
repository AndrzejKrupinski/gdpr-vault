<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Email::class, function (Faker $faker) {
    return [
        'address' => $faker->safeEmail,
    ];
});
