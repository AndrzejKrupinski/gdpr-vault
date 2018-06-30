<?php

use Faker\Generator as Faker;

$factory->define(App\Models\PersonalDetail::class, function (Faker $faker, array $attributes) {
    $personId = $attributes['person_id'] ?? factory(App\Models\Person::class)->create()->getKey();

    return [
        'person_id' => $personId,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'middle_name' => $faker->optional(0.25)->firstName,
        'maiden_name' => $faker->optional(0.25)->lastName,
        'date_of_birth' => random_int(0, 100) < 20
            ? $faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d')
            : null,
        'sex' => $faker->randomElement([0, 1, 2, 9]),
        'marital_status' => $faker->randomElement(range(0, 5)),
    ];
});
