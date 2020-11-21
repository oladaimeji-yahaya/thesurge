<?php

Route::group(['prefix' => 'coinpayment','namespace'=>'Coinpayment','as'=>'coinpayment.'], function () {
    Route::get('/verifypayment', function (Illuminate\Http\Request $request) {
        return ['status'=> App\Library\Coinpayment\Models\CoinpaymentTrxLog::where('reference', $request->reference)->count()];
    })->name('verifypayment');
    Route::post('/ipn', 'CoinpaymentController@receive_webhook')
            ->middleware('web')
            ->name('ipn.received');
});
