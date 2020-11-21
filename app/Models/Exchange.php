<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    protected $fillable = ['rank','name','symbol','price_usd','price_btc'];
    
    public static function set($key, array $values)
    {
        return self::where('key', $key)->update($values);
    }
    
    public static function get($key)
    {
        return self::where('key', $key)->first();
    }

    public static function symbol($symbol)
    {
        return self::where('symbol', $symbol)->first();
    }
    
    public static function updateData(array $rates)
    {
        foreach ($rates as $value) {
            /* @var $exchange Exchange */
            $exchange = static::firstOrNew(['key' => $value['id']]);
            $exchange->key = $value['id'];
            $exchange->fill($value);
            $exchange->save();
        }
    }
    
    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }
}
