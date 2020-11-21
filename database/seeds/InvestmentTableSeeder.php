<?php

use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class InvestmentTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment() === 'local') {
            $john = User::find(1);
            $users = User::inRandomOrder()->limit(30)
                ->get()
                ->push($john);
            $plans = Plan::all();
            /** @var User $user */
            foreach ($users as $user) {
                try {
                    $plan = $plans->random();
                    $amount = rand(intval($plan->minimum), 20000);
                    $investment = $user->invest($amount, $plan);

                    if ($investment && rand(0, 4) > 1) {
                        $investment->due_at = Carbon::now();
                        $investment->expire_at = Carbon::now();
                        $investment->paid_at = Carbon::now()->addDays(-$plan->duration);
                        $investment->verified_at = Carbon::now()->addDays(-$plan->duration);
                        $investment->save();
                    }
                } catch (Exception $e) {
                    echo $e->getMessage() . PHP_EOL;
                }
            }
        }
    }
}
