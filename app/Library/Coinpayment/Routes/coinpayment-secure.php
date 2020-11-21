<?php

Route::group(['prefix' => 'coinpayment','namespace'=>'Coinpayment','as'=>'coinpayment.'], function () {
    Route::get('/trasactions', 'CoinpaymentController@transactions')
            ->name('trasactions');
});
