<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AffiliateApplication extends Model
{
    protected $fillable = [
        'name',
        'location',
        'email',
        'phone',
        'message',
        'forwarded_to',
    ];
}
