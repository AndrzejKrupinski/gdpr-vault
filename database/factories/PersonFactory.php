<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Person::class, function (Faker $faker) {
    return [
        'site' => $faker->randomNumber(2),
    ];
});
