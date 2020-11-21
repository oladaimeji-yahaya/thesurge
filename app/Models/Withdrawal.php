<?php

namespace App\Models;

use App\Models\Traits\HasReference;
use App\Models\Traits\Transaction;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdrawal extends Model {

    use HasReference;
    use SoftDeletes;

    protected $casts = [
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

}
