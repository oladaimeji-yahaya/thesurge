<?php

namespace App\Models;

use App\Models\Traits\HasReference;

class BonusLog extends Model
{
    use HasReference;

    protected $dates = ['due_at'];
    protected $casts = [
        'extra' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isDue()
    {
        return empty($this->due_at) ? true : $this->due_at->isPast();
    }
    
    public function source()
    {
        return $this->morphTo('source');
    }
}
