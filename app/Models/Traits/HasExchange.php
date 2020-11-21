<?php

namespace App\Models\Traits;

use App\Models\Exchange;

trait HasExchange
{
    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }
}
