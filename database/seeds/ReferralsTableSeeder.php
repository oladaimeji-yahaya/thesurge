<?php

use App\Models\Referral;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReferralsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment() === 'local') {
            $me = User::find(1);
            $users = User::all()->except($me->getKey())->random(40);
            $used = [1];
            foreach ($users as $user) {
                $referral = new Referral;
                $referral->user_id = array_random($used);
                $referral->referred = $user->id;
                $referral->save();
                $used[] = $user->id;
            }
        }
    }
}
