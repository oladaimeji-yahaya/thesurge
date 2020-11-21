<?php

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $plans = [
                [
                'name' => 'Gold',
                'minimum' => 200,
                'maximum' => 999,
                'compounding' => 200,
                'rate' => 1.3,
                'incubation' => 1,
                'duration' => 7
            ],
            [
                'name' => 'Diamond',
                'minimum' => 1000,
                'maximum' => 50000,
                'compounding' => 1000,
                'rate' => 1.6,
                'incubation' => 1,
                'duration' => 7
            ],
            [
                'name' => 'Platinum',
                'minimum' => 50000,
                'maximum' => 9999999,
                'compounding' => 50000,
                'rate' => 2,
                'incubation' => 1,
                'duration' => 7
            ],
           
        ];

        foreach ($plans as $plan) {
            $p = new Plan;
            foreach ($plan as $attr => $value) {
                $p->$attr = $value;
            }
            $p->save();
        }
    }

}
