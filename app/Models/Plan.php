<?php

namespace App\Models;

class Plan extends Model
 {

    public function investments()
    {
        return $this->belongsToMany(Investment::class);
    }
    
    public function getDailyRate($amount)
    {
        $interest = $amount * ($this->rate / 100);
        $rounds = $this->duration/$this->incubation;
        $totalInterest = $interest * $rounds;
//        return ($amount + $totalInterest) / $this->duration;
        return $totalInterest / $this->duration;
    }
}
