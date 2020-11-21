<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matrix extends Model
{
    protected $fillable = [
        'user_id',
        'sponsor_id',
        'affiliate_level_id'
    ];
    
    public function referral()
    {
        return $this->belongsTo(Referral::class, 'referral_id');
    }
    
    public function investment()
    {
        return $this->belongsTo(Investment::class, 'investment_id');
    }
    
    public function level()
    {
        return $this->belongsTo(AffiliateLevel::class, 'affiliate_level_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }
}
