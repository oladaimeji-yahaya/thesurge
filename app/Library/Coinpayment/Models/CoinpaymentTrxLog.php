<?php

namespace App\Library\Coinpayment\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CoinpaymentTrxLog extends Model
{
    protected $guarded = ['id'];
    protected $dates = [
        'payment_created_at',
        'expired',
        'confirmation_at'
    ];
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function isComplete()
    {
        return $this->status >= 100;
    }
    
    public function wireStatus()
    {
        $status = '';
        switch ($this->wire_status) {
            case 2:
                $status = 'Success';
                break;
            case 1:
                $status = 'Pending';
                break;
            case -1:
            default:
                $status = $this->wire_address?:'Unknown';
                break;
        }
        return $status;
    }
}
