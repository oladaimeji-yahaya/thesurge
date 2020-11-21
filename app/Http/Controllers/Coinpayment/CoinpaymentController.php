<?php

namespace App\Http\Controllers\Coinpayment;

use App\Http\Controllers\Controller;
use App\Library\Coinpayment\Models\CoinpaymentTrxLog;
use App\Library\Coinpayment\Traits\CoinpaymentControllerTrait;
use App\Models\Investment;
use function setting;
use function view;

class CoinpaymentController extends Controller
{
    use CoinpaymentControllerTrait;

    public function transactions()
    {
        $transactions = CoinpaymentTrxLog::with('user')
                        ->latest()->paginate();
        $references = $transactions->getCollection()->pluck('reference')->toArray();
        $investments = Investment::whereIn('reference', $references)->get();
        $wire_address = setting('wallet_address');
        return view('admin.coinpayment.transactions', compact('transactions', 'investments', 'wire_address'));
    }
}
