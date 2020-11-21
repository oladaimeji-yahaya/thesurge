<?php

use App\Library\Coinpayment\Models\CoinpaymentTrxLog;

return [
    /*
     * @required: true
     * Create an acount and het Api Key on this site https://www.coinpayments.net
     */
    'public_key' => env('COIN_PAYMENT_PUBLIC_KEY', ''),
    'private_key' => env('COIN_PAYMENT_PRIVATE_KEY', ''),
    /*
     * IPN Configuration
     */
    'coinpayment_merchant_id' => env('COIN_PAYMENT_MERCHANT_ID', ''),
    'coinpayment_ipn_secret' => env('COIN_PAYMENT_IPN_SECRET', ''),
    'coinpayment_ipn_debug_email' => env('COIN_PAYMENT_IPN_DEBIG_EMAIL', ''),
    /*
     * Supported currencies
     * @currecies : USD, CAD, EUR, ARS, AUD, AZN, BGN, BRL, BYN, CHF, CLP, CNY, COP, CZK
     * DKK, GBP, GIP, HKD, HUF, IDR, ILS, INR, IRR, IRT, ISK, JPY, KRW, LAK, MKD, MXN, ZAR,
     * MYR, NGN, NOK, NZD, PEN, PHP, PKR, PLN, RON, RUB, SEK, SGD, THB, TRY, TWD, UAH, VND,
     */
    'default_currency' => 'USD',
    /*
     * Header config
     */
    'header_type' => 'logo', // @option-value: logo|text
    'header_logo' => '/coinpayment.logo.png', // this is a Path Assets file
    'header_text' => 'Your Payment Summary',
    /*
     * menu in histori transaction page
     */
    'menus' => [
        'Home' => [
            'url' => '/', // only link path
            'class_icon' => 'fa fa-home'
        ],
    // 'Foo' => [
    //   'url' => '/foo',
    //   'class_icon' => 'fa fa-home'
    // ]
    ],
    'rate' => 0.005,
    'app_rate' => 0.05,
    'investment_table' => 'investment-table-class',
    'post_payment' => function (CoinpaymentTrxLog $log) {
//        $investment = Investment::findByReference($log->reference);
//        $now = Carbon::now();
//        if(is_object($investment)){
//            $investment->paid_at = $investment->paid_at ?: $now;
//            return $investment->save();
//        }
    },
    'post_verify' => function (CoinpaymentTrxLog $log) {
//        $investment = Investment::findByReference($log->reference);
//        $now = Carbon::now();
//        if(is_object($investment)){
//            $investment->paid_at = $investment->paid_at ?: $now;
//            $investment->verified_at =  $investment->verified_at ?:$now;
//            return $investment->save();
//        }
    }
];
