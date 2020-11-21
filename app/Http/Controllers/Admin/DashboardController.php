<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BTCExchangeRate;
use App\Models\BTCInfo;
use App\Models\Exchange;
use App\Models\Investment;
use App\Models\User;
use App\Models\Withdrawal;
use function getBTCInfo;
use function getBTCRates;
use function jsonOrRedirectBack;
use function view;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'admin_count' => User::where('admin', true)->count(),
            'users_count' => User::where('admin', false)->count(),
            'queue_count' => Withdrawal::unpaid()->count(),
            'paid_investment_count' => Investment::paid()->unverified()->count(),
            'investment_count' => Investment::count(),
            'btcrates' => BTCExchangeRate::all(),
            'exchanges' => Exchange::orderBy('rank')->get()
        ];
        return view('admin.dashboard', $data);
    }

    public function updateRates()
    {
        $rates = getBTCRates();
        BTCExchangeRate::updateData($rates);
        return jsonOrRedirectBack();
    }

    public function updateInfo()
    {
        $info = getBTCInfo();
        BTCInfo::updateData($info);
        return jsonOrRedirectBack();
    }
}
