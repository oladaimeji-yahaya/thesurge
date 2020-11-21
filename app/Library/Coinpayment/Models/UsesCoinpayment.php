<?php

namespace App\Library\Coinpayment\Models;

trait UsesCoinpayment
{
    public function coinpayment_transactions()
    {
        return $this->hasMany(CoinpaymentTrxLog::class, 'user_id');
    }
}
