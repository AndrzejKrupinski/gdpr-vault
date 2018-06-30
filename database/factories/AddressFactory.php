<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Address::class, function (Faker $faker) {
    return [
        'street' => $faker->streetAddress,
        'city' => $faker->city,
        'postcode' => $faker->postcode,
        'state' => $faker->state,
        'country' => $faker->country,
    ];
});
