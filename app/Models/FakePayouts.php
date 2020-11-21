<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FakePayouts extends Model
{
    protected $fillable = [
        'username', 'amount', 'confirmations', 'address', 'country', 'txid'
    ];

    public function getBlockChainAddressURL()
    {
        return "https://blockchain.info/address/{$this->address}";
    }

    public function getBlockChainTxIDURL()
    {
        return "https://blockchain.info/tx/{$this->address}";
    }
}
