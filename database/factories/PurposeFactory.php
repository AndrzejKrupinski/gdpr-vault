<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Purpose::class, function (Faker $faker) {
    return [
        'description' => 'purpose.description',
    ];
});
