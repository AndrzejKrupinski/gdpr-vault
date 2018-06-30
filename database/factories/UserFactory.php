<?php

use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker, array $attributes) {
    return [
        'ClientID' => $attributes['ClientID'] ?? $faker->randomNumber(),
        'UserName' => $attributes['UserName'] ?? $faker->name,
        'UserEmail' => $attributes['UserEmail'] ?? $faker->unique()->safeEmail,
        'UserHash' => $attributes['UserHash'] ?? legacy_hash('password'),
        'UserLogin' => $attributes['UserLogin'] ?? $faker->userName,
    ];
});

$factory->state(App\Models\User::class, 'admin', [
    'UserLogin' => 'profitroom',
    'ClientID' => config('auth.legacy.super_user_id'),
]);
