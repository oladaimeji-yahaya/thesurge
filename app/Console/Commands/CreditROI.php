<?php

namespace App\Console\Commands;

use App\Models\Investment;
use Illuminate\Console\Command;

class CreditROI extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credit:roi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Credit ROI';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Update ROI for investments
        $investments = Investment::paid()->verified()
            ->unexpired()->where('auto_roi', true)->get();
        foreach ($investments as $investment) {
            $plan = $investment->plan;
            $investment->expire_at = $investment->paid_at->addDays($plan->duration + 1);
            $investment->due_at = $investment->paid_at->addDays($plan->incubation);

            $num_of_days = $investment->paid_at->diffInHours();
            if ($plan->compounding > 0) {
                //When compounding, the amount + interest is reused as the capital at intervals
                $amount = $earnings = $investment->amount;
                for ($i = 1; $i <= $num_of_days; $i++) {
                    $CI = $amount * ($plan->rate / 100);
                    //Current daily rate
                    $investment->daily_rate = $CI / $plan->incubation;
                    $earnings += $investment->daily_rate;
                    //If end of interval, compound if amount > $plan->compounding
                    if ($i % $plan->incubation === 0 && $earnings >= $plan->compounding) {
                        $amount = $earnings;
                    }
                }
                $investment->roi = $earnings - $investment->amount;
            } else {
                //Recalculate with new profit algo
                $rounds = $plan->duration / $plan->incubation;
                if($rounds > 1){
                    $interest = $investment->amount * ($plan->rate / 100);
                    $totalInterest = $interest * $rounds;
                    //Fix: Recalculate daily rate
                    $investment->daily_rate = $totalInterest / $plan->duration;

                    $investment->roi = $num_of_days * $investment->daily_rate;
                }else{
                    $interest = $investment->amount * ($plan->rate / 100);
                    //Fix: Recalculate daily rate
                    $investment->daily_rate = $interest / $plan->duration;

                    $investment->roi = $num_of_days * $investment->daily_rate;
                }
            }

            $investment->save();
        }

        //Update ROI for bonuses
        //Not implemented for now

    }
}
