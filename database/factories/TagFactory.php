<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Tag::class, function (Faker $faker, array $attributes) {
    return [
        'tag' => $attributes['tag'] ?? $faker->word,
    ];
});

