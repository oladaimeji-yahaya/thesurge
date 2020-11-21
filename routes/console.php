<?php

use App\Models\BonusLog;
use App\Models\BTCExchangeRate;
use App\Models\BTCInfo;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
  |--------------------------------------------------------------------------
  | Console Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of your Closure based console
  | commands. Each Closure is bound to a command instance allowing a
  | simple approach to interacting with each command's IO methods.
  |
 */

Artisan::command('inspire', function () {
//    $this->comment(Inspiring::quote());
    
    $client_id = setting('COIN_PAYMENT_CLIENT_ID');
    $merchant_id = setting('COIN_PAYMENT_MERCHANT_ID');
    $this->info($client_id."\n". $merchant_id);
});

Artisan::command('update:xchcount', function () {
    $count = setting('xchcount', 495320);
    $count += rand(1, 6);
    setting(['xchcount' => $count]);
});

Artisan::command('update:adminurl', function () {
    setting(['ADMIN_PREFIX' => random_chars()]);
})->describe("Update admin URL");

Artisan::command('update:btcrates', function () {
    $rates = getBTCRates();
    if (is_array($rates)) {
        BTCExchangeRate::updateData($rates);
    }
});

Artisan::command('update:btcinfo', function () {
    $info = getBTCInfo();
    if (is_array($info)) {
        BTCInfo::updateData($info);
    }
});



Artisan::command('testmails', function () {
    /** @var \App\Models\User $user */
    $user = \App\Models\User::find(1);
    /** @var \App\Models\Investment $investment */
    $investment = $user->investments()->first();
    /** @var \App\Models\Withdrawal $withdrawal */
    $withdrawal = $user->withdrawals()->first();

    $user->notify(new \App\Notifications\Welcome());
    $user->notify(new \App\Notifications\DepositVerified($investment));
    $user->notify(new \App\Notifications\Referral(\App\Models\User::find(2)));
    $user->notify(new \App\Notifications\Suspended());
    $user->notify(new \App\Notifications\WithdrawalRequestApproved($withdrawal));
    $user->notify(new \App\Notifications\WithdrawalRequestPaid($withdrawal));

    $bonus = new BonusLog;
    $bonus->amount = 20000;
    $bonus->name = 'Registration Bonus';
//    $bonus->source()->associate($user);
    $user->notify(new \App\Notifications\Bonus($bonus));
});

