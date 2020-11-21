<?php

namespace App\Models;

class Referral extends Model
{
    protected $fillable = ['referred'];

    protected static function boot()
    {
        static::creating(function ($referral) {
            //Avoid duplicates
            $copy = Referral::where('user_id', $referral->user_id)
                            ->where('referred', $referral->referred)->first();
            return !is_object($copy);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred');
    }

    public function isActive()
    {
        return $this->is_confirmed > 0 && !$this->is_used;
    }

    public function status()
    {
        if ($this->is_used) {
            return 'used';
        } elseif ($this->is_confirmed > 0) {
            return 'confirmed';
        } else {
            return 'pending';
        }
    }
}
