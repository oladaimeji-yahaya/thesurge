<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateLevel extends Model
{
    public function users()
    {
        return $this->hasMany(User::class, 'affiliate_level_id');
    }
}
