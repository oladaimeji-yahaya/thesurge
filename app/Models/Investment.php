<?php

namespace App\Models;

use App\Models\Traits\HasExchange;
use App\Models\Traits\HasReference;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Exchange exchange
 */
class Investment extends Model
{
    use HasReference;
    use SoftDeletes;
    use HasExchange;

    protected $casts = [
        'due_at' => 'datetime',
        'expire_at' => 'datetime',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
