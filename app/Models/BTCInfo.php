<?php

namespace App\Models;

class BTCInfo extends Model {

    protected $table = 'btc_info';
    protected $fillable = ['key'];

    public static function updateData(array $btcinfo)
    {
        foreach ($btcinfo as $key => $value) {
            $info = static::firstOrNew(['key' => $key]);
            $info->key = $key;
            $info->value = $value;
            $info->label = ucfirst(self::inferLabel($key));
            $info->save();
        }
    }

    public static function inferLabel($key)
    {
        $no_ = str_replace('_', ' ', $key);
        $noTx = str_replace(' tx', ' transactions', $no_);
        $noN = preg_replace('/^n\\s/', 'no. of ', $noTx);
        return $noN;
    }

}
