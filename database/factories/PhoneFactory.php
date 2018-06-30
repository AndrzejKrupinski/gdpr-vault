<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Phone::class, function (Faker $faker) {
    return [
        'number' => $faker->e164PhoneNumber,
    ];
});
