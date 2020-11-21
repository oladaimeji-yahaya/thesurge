<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class WithdrawalsTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment() === 'local') {
            $john = User::find(1);
            $users = User::inRandomOrder()->limit(20)
                    ->get()
                    ->push($john);
            foreach ($users as $user) {
                try {
                    $balance = $user->withdrawableBalance();
                    if ($balance) {
                        $amount = min([rand(20, 1000), $balance]);
                        $user->withdraw($amount);
                    }
                } catch (Exception $e) {
                    echo $e->getMessage() . PHP_EOL;
                }
            }
        }
    }

}
