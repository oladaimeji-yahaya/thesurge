<?php

use App\Models\Account;
use App\Models\Investment;
use App\Models\Referral;
use App\Models\User;
use App\Models\Withdrawal;
use Faker\Generator;

/*
  |--------------------------------------------------------------------------
  | Model Factories
  |--------------------------------------------------------------------------
  |
  | Here you may define all of your model factories. Model factories give
  | you a convenient way to create models for testing and seeding your
  | database. Just tell the factory how a default model should look.
  |
 */

$factory->define(User::class, function (Generator $faker) {
    static $password;
    $username = $faker->userName;
    return [
        'username' => $username,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'phone' => $faker->phoneNumber,
        'slug' => $username,
        'email' => $faker->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'preferences' => json_encode(User::getDefaultPreferences()),
    ];
});

$factory->define(Referral::class, function (Generator $faker) {
    $users = User::all();
    static $user;
    $user = $user ?: $users->random();
    return [
        'user_id' => $user->id,
        'referred' => $users->except($user->getKey())->random()->id,
        'is_confirmed' => $faker->boolean(),
        'is_used' => $faker->boolean(),
    ];
});

$factory->define(Account::class, function (Generator $faker) {
    $user = User::inRandomOrder()->limit(1)->first();
    return [
        'user_id' => $user->id,
        'name' => $user->name,
        'bank' => $faker->company,
        'number' => $faker->bankAccountNumber,
    ];
});

$factory->define(Withdrawal::class, function (Generator $faker) {
    $user = User::inRandomOrder()->limit(1)->first();
    return [
        'user_id' => $user->id,
        'amount' => rand(1000, 10000),
    ];
});

$factory->define(Investment::class, function (Generator $faker) {
    return [
        'user_id' => User::all()->random()->id,
        'amount' => rand(1, 50),
    ];
});
