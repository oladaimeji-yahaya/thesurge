<?php

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $preferences = json_encode(User::getDefaultPreferences());
        $faker = Factory::create();
        $me = new User;
        $me->username = 'admin-user';
        $me->first_name = $faker->firstNameFemale;
        $me->last_name = $faker->lastName;
        $me->slug = 'admin';
        $me->phone = '07012345678';
        $me->email = 'admin@domain.com';
        $me->admin = true;
        $me->password = bcrypt('secret-pwd');
        $me->preferences = $preferences;
        $me->save();


        if (app()->environment() === 'local') {
            factory(User::class, 50)->create();
        }
    }
}
