<?php

use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->states('admin')->create();
        factory(User::class, 2)->create();

        $clients = User::pluck('ClientID')->unique();
        $faker = app(Faker::class);

        foreach (range(1,100) as $site)
        {
            app('db')->table('Sites')->insert([
                'SiteID' => $site,
                'ClientID' => $clients->random(),
                'SiteName' => $faker->word,
                'SiteKey' => $faker->word,
            ]);
        }
    }
}
