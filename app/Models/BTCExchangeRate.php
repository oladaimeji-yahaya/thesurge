<?php

namespace App\Models;

class BTCExchangeRate extends Model {

    protected $table = 'btc_exchange_rates';
    protected $fillable = ['currency'];

    public static function updateData(array $rates)
    {
        foreach ($rates as $currency => $data) {
            $rate = static::firstOrNew(['currency' => $currency]);
            foreach ($data as $key => $datum) {
                if ($key === '15m') {
                    $key = '_' . $key;
                }
                $rate->$key = $datum;
            }
            $rate->save();
        }
    }

}
