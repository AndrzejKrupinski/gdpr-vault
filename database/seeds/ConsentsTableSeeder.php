<?php

use Illuminate\Database\Seeder;

class ConsentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Consent::class, 2)->create()->each(
            function ($consent) {
                $consent->purposes()->save(factory(App\Models\Purpose::class)->create());

                $person = $consent->people()->first();
                $person->personalDetails()->save(
                    factory(App\Models\PersonalDetail::class)->make(['person_id' => $person->getKey()])
                );
                $person->emails()->saveMany(factory(App\Models\Email::class, random_int(0, 2))->make());
                $person->phones()->saveMany(factory(App\Models\Phone::class, random_int(0, 2))->make());
                $person->addresses()->saveMany(factory(App\Models\Address::class, random_int(0, 2))->make());
            }
        );
    }
}
