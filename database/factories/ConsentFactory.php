<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Consent::class, function (Faker $faker) {
    return [
        'person_id' => factory(App\Models\Person::class),
        'content_id' => factory(App\Models\Content::class),
        'expired_at' => now()->addMonths(2),
    ];
});

$factory->state(App\Models\Consent::class, 'confirmed', [
    'confirmed' => true
]);
