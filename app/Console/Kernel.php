<?php

namespace App\Console;

use App\Console\Commands\CreditReferrals;
use App\Console\Commands\CreditROI;
use App\Console\Commands\UpdateRates;
use App\Library\Coinpayment\Commands\CheckTransactions;
use App\Library\Coinpayment\Commands\EnableIPN;
use App\Library\Coinpayment\Commands\FundMerchant;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use function base_path;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CreditROI::class,
        CreditReferrals::class,
        EnableIPN::class,
        CheckTransactions::class,
        FundMerchant::class,
        UpdateRates::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('credit:referral')->everyThirtyMinutes();
        $schedule->command('credit:roi')->everyThirtyMinutes();
        $schedule->command('update:xchcount')->everyThirtyMinutes();
        $schedule->command('update:adminurl')->daily();
        $schedule->command('update:rates')->hourly();
        //Update rates
//        $schedule->command('update:btcrates')->hourly();
        //Update info
//        $schedule->command('update:btcinfo')->everyTenMinutes();
        
        // If IPN is enable set the schedule for ->daily()
        // And if IPN is disable set schedule for ->everyMinute()
        $schedule->command('coinpayment:transaction-check')->daily();
        $schedule->command('coinpayment:fund-merchant')->everyThirtyMinutes();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
