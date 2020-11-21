<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FakeTeam extends Model
{
    protected $fillable = [
        'name', 'title', 'image', 'linkedin_url', 'email'
    ];
}
