<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Investment;
use App\Models\Plan;
use App\Notifications\DepositVerified;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function jsonOrRedirectBack;
use function view;

class InvestmentsController extends Controller
{
    public function index(Request $request)
    {
        $investments = Investment::with('user')
            ->withTrashed()
            ->with('files')
            ->with('plan')
            ->latest();
        if ($request->q) {
            $investments->where('reference', $request->q);
        }
        if ($request->filter === 'pending') {
            $investments->paid()->unverified();
        } elseif ($request->plan) {
            $investments->where('plan_id', $request->plan);
        }

        return view('admin.investments.index', [
            'investments' => $investments->paginate(10),
            'plans' => Plan::all()
        ]);
    }

    public function confirmPayment(Request $request)
    {
        //Check if user is the wallet user
        /* @var $investment Investment */
        $investment = Investment::findByReference($request->input('id'));
        if (is_object($investment)) {
            //mark confirmed
            if (!$investment->paid_at) {
                $investment->paid_at = Carbon::now();
            }
            $investment->verified_at = Carbon::now();
            $investment->user->notify(new DepositVerified($investment));
            $data = ['status' => true, 'message' => 'Payment verified'];
        } else {
            $data = ['status' => false, 'message' => 'We couldn\'t find this match'];
        }
        return jsonOrRedirectBack($data);
    }

    public function edit(Request $request, $reference)
    {
        /* @var $investment Investment */
        $investment = Investment::withTrashed()->where('reference', $reference)->first();
        return view('admin.investments.edit', [
            'investment' => $investment,
//            'plans' => Plan::all()
        ]);
    }

    public function update(Request $request, $reference)
    {
        /* @var $investment Investment */
        $investment = Investment::withTrashed()->where('reference', $reference)->first();
        abort_unless(is_object($investment), 404);

        $this->validate($request, [
            'amount' => 'required|numeric',
            'btc' => 'required|numeric',
            'paid_at' => 'nullable|date',
            'roi' => 'required|numeric',
            'auto_roi' => 'required|boolean',
            'verified_at' => 'nullable|date',
        ]);

        $investment->amount = $request->amount;
        $investment->btc = $request->btc;
        $investment->roi = $request->roi;
        $investment->auto_roi = !!$request->auto_roi;
        $investment->paid_at = $request->paid_at ? Carbon::parse($request->paid_at) : null;
        $investment->verified_at = $request->verified_at ? Carbon::parse($request->verified_at) : null;

        $investment->save();
        return jsonOrRedirectBack(['status' => true, 'message' => 'Investment details updated successfully']);
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
                Investment::whereIn('id', $in['id'])->delete();
                $response['message'] = $count . " request$s Deleted";
                break;
            case 'verify':
                Investment::whereIn('id', $in['id'])
                    ->where('paid_at', null)
                    ->update(['paid_at' => Carbon::now()]);
                Investment::whereIn('id', $in['id'])
                    ->update(['verified_at' => Carbon::now()]);
                //Notify users
                $investments = Investment::with('user')->findMany($in['id']);
                foreach ($investments as $investment) {
                    $investment->user->notify(new DepositVerified($investment));
                }

                $response['message'] = $count . " request$s verified";
                break;
        }

        if (empty($response['message'])) {
            $response = ['status' => false, 'message' => 'Invalid Request.'];
        }
        return $response;
    }
}
