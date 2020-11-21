<?php

namespace App\Models;

class Country extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function scopeRelevant()
    {
        return self::orderBy('relevance', 'desc');
    }

    public static function findByIso($iso)
    {
        return self::where('iso', $iso)->first();
    }

    public static function findByIso3($iso3)
    {
        return self::where('iso3', $iso3)->first();
    }
}
