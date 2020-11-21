<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Notifications\WithdrawalRequestApproved;
use App\Notifications\WithdrawalRequestPaid;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function view;

class WithdrawalsController extends Controller
{
    public function index(Request $request)
    {
        $withdrawals = Withdrawal::with('user')->withTrashed()->latest();
        if ($request->q) {
            $withdrawals->where('reference', $request->q);
        }
        if ($request->filter === 'pending') {
            $withdrawals->unpaid();
        }

        return view('admin.withdrawals.index', ['withdrawals' => $withdrawals->paginate(10)]);
    }

    public function edit(Request $request, $reference)
    {
        /* @var $withdrawal Withdrawal */
        $withdrawal = Withdrawal::withTrashed()->where('reference', $reference)->first();
        return view('admin.withdrawals.edit', [
            'withdrawal' => $withdrawal,
//            'plans' => Plan::all()
        ]);
    }

    public function update(Request $request, $reference)
    {
        /* @var $withdrawal Withdrawal */
        $withdrawal = Withdrawal::withTrashed()->where('reference', $reference)->first();
        abort_unless(is_object($withdrawal), 404);

        $this->validate($request, [
            'amount' => 'required|numeric',
            'btc' => 'required|numeric',
            'paid_at' => 'nullable|date',
            'verified_at' => 'nullable|date',
        ]);

        $withdrawal->amount = $request->amount;
        $withdrawal->btc = $request->btc;
        $withdrawal->paid_at = $request->paid_at ? Carbon::parse($request->paid_at) : null;
        $withdrawal->approved_at = $request->approved_at ? Carbon::parse($request->approved_at) : null;

        $withdrawal->save();
        return jsonOrRedirectBack(['status' => true, 'message' => 'Withdrawal details updated successfully']);
    }

    public function manageList(Request $request)
    {
        $this->validate($request, [
            'action' => 'required',
            'id' => 'required|array'
                ], [
            'id.required' => 'Select 1 or more items'
        ]);

        $in = $request->input();
        $count = count($in['id']);
        $s = $count > 1 ? 's' : '';

        $response['status'] = true;
        switch ($in['action']) {
            case 'delete':
                Withdrawal::whereIn('id', $in['id'])->delete();
                $response['message'] = $count . " request$s deleted";
                break;
            case 'approve':
                Withdrawal::whereIn('id', $in['id'])
                        ->update(['approved_at' => Carbon::now()]);
                //Notify users
                $withdrawals = Withdrawal::with('user')->findMany($in['id']);
                foreach ($withdrawals as $withdrawal) {
                    $withdrawal->user->notify(new WithdrawalRequestApproved($withdrawal));
                }
                $response['message'] = $count . " request$s approved";
                break;
            case 'paid':
                $now = Carbon::now();
                Withdrawal::whereIn('id', $in['id'])
                        ->update([
                            'approved_at' => $now,
                            'paid_at' => $now,
                ]);
                //Notify users
                $withdrawals = Withdrawal::with('user')->findMany($in['id']);
                foreach ($withdrawals as $withdrawal) {
                    $withdrawal->user->notify(new WithdrawalRequestPaid($withdrawal));
                }

                $response['message'] = $count . " request$s marked paid";
                break;
        }

        if (empty($response['message'])) {
            $response = ['status' => false, 'message' => 'Invalid Request.'];
        }
        return $response;
    }
}
