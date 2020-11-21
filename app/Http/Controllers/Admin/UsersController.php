<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateLevel;
use App\Models\BonusLog;
use App\Models\Exchange;
use App\Models\Matrix;
use App\Models\Plan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use function abort_unless;
use function request;
use function to_currency;
use function view;
use const USER_STATUS_ACTIVE;
use const USER_STATUS_SUSPENDED;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::withTrashed()->where('admin', false);

        $filter = request('filter');
        switch ($filter) {
            case 'suspended':
                $users->where('status', USER_STATUS_SUSPENDED);
                break;
            case 'deleted':
                $users->where('deleted_at', '<>', null);
                break;
        }

        $query = request('q');
        if ($query) {
            $users->where(function ($q) use ($query) {
                return $q->where('username', 'like', "$query%")
                    ->orWhere('first_name', 'like', "%$query%")
                    ->orWhere('last_name', 'like', "%$query%")
                    ->orWhere('email', $query);
            });
        }

        return view('admin.users.index', [
            'users' => $users->latest()->paginate(10)
        ]);
    }

    public function view($user)
    {
        /** @var User $user */
        $user = User::where('id', $user)
            ->withTrashed()
            ->first();
        abort_unless(is_object($user), 404);
        $data = $this->getCalculatorDetails();
        $data['user'] = $user;
        $data['investments'] = $user->investments()
            ->withTrashed()->with('exchange')->with('plan')
            ->latest()->get();
        $data['withdrawals'] = $user->withdrawals()->withTrashed()
            ->latest()->get();
        $data['bonuses'] = $user->bonuses()->latest()->get();

        $data['investments_amount'] = $user->investments()->paid()
            ->verified()->sum('amount');
        $data['withdrawals_amount'] = $user->withdrawals()
            ->paid()->sum('amount');

        return view('admin.users.view', $data);
    }

    public function viewReferrals(Request $request, $user)
    {
        /** @var User $user */
        $user = User::where('id', $user)
            ->withTrashed()
            ->first();
        abort_unless(is_object($user), 404);
        $data['user'] = $user;

        $r = $user->referrals()
            ->with('user')
            ->latest();
        $r2 = clone $r;
        $data['referrals'] = $r->paginate();
        $data['referralsCount'] = $r2->count();
        $data['used'] = $user->usedReferrals->count();
        $data['confirmed'] = $user->activeReferrals->count();
        $data['pending'] = $user->pendingReferrals->count();

        return view('admin.users.referrals', $data);
    }

    public function viewAffiliates(Request $request, $user)
    {
        $user = User::where('id', $user)
            ->withTrashed()
            ->first();
        abort_unless(is_object($user), 404);
        $data['user'] = $user;

        $data['levels'] = AffiliateLevel::all();
        $matrices = Matrix::where(['sponsor_id' => $user->id])
            ->with('user')
            ->with('level')
            ->with('referral.user');
        if ($request->level) {
            $matrices->where('affiliate_level_id', $request->level);
        }
        $data['matrices'] = $matrices->orderBy('affiliate_level_id')->paginate();

        return view('admin.users.affiliate', $data);
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

        switch ($in['action']) {
            case 'makeadmin':
                User::whereIn('id', $in['id'])->update(['admin' => 1]);
                return ['status' => true, 'message' => $count . " admin$s added"];
            case 'removeadmin':
                User::whereIn('id', $in['id'])->update(['admin' => 0]);
                return ['status' => true, 'message' => $count . " admin$s removed"];
            case 'block':
                User::whereIn('id', $in['id'])->update(['status' => USER_STATUS_SUSPENDED]);
                return ['status' => true, 'message' => $count . " user$s suspended"];
            case 'unblock':
                User::whereIn('id', $in['id'])->update(['status' => USER_STATUS_ACTIVE]);
                return ['status' => true, 'message' => $count . " user$s activated"];
            case 'delete':
                User::whereIn('id', $in['id'])->delete();
                return ['status' => true, 'message' => $count . " user$s deleted"];
            case 'restore':
                User::whereIn('id', $in['id'])->restore();
                return ['status' => true, 'message' => $count . " user$s restored"];
            case 'discard':
                User::whereIn('id', $in['id'])->forceDelete();
                return ['status' => true, 'message' => $count . " user$s deleted permanently."];
            case 'add_affiliate':
                User::whereIn('id', $in['id'])->update(['super_affiliate' => true]);
                return ['status' => true, 'message' => $count . " super affiliate$s added"];
            case 'remove_affiliate':
                User::whereIn('id', $in['id'])->update(['super_affiliate' => false]);
                return ['status' => true, 'message' => $count . " super affiliate$s removed"];
            case 'freeze_withdrawal':
                User::whereIn('id', $in['id'])->update(['withdrawal_frozen' => true]);
                return ['status' => true, 'message' => $count . " user$s withdrawal frozen"];
            case 'activate_withdrawal':
                User::whereIn('id', $in['id'])->update(['withdrawal_frozen' => false]);
                return ['status' => true, 'message' => $count . " user$s withdrawal activated"];
        }

        return ['status' => false, 'message' => 'Invalid Request.'];
    }

    public function deposit(Request $request)
    {
        $user_id = $request->user;
        $amount = $request->amount;
        $plan = Plan::find($request->plan);
        abort_unless(is_object($plan), 422, 'Plan does not exist');
        /* @var $user User */
        $user = User::find($user_id);
        abort_unless(is_object($user), 422, 'User does not exist');
        try {
            $this->validateInvestmentData($user, $plan, $amount);
            $user->invest($amount, $plan, true);
            $data['status'] = true;
            $data['message'] = 'Deposit was successful';
        } catch (Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }
        return $data;
    }

    public function reinvest(Request $request)
    {
        $user_id = $request->user;
        $amount = $request->amount;
        $plan = Plan::find($request->plan);
        abort_unless(is_object($plan), 422, 'Plan does not exist');
        /* @var $user User */
        $user = User::find($user_id);
        abort_unless(is_object($user), 422, 'User does not exist');
        try {
            $this->validateInvestmentData($user, $plan, $amount);
            $user->reinvest($amount, $plan, true);
            $data['status'] = true;
            $data['message'] = 'Reinvestment was successful';
        } catch (Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }
        return $data;
    }

    public function withdraw(Request $request)
    {
        $user_id = $request->user;
        /* @var $user User */
        $user = User::find($user_id);
        abort_unless(is_object($user), 422, 'User not found');

        try {
            $amount = $request->input('amount');
            if (!is_numeric($amount)) {
                throw new Exception('Invalid amount');
            }
            $balance = $user->withdrawableBalance();
            if ($amount > $balance) {
                throw new Exception('You can not withdraw more than '
                    . to_currency($balance));
            }
            $user->withdraw($amount, true);

            $data['status'] = true;
            $data['message'] = 'Request created successfully';
        } catch (Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }


        return jsonOrRedirectBack($data);
    }

    public function payBonuses(Request $request)
    {
        $user_id = $request->user;
        /* @var $user User */
        $user = User::find($user_id);
        abort_unless(is_object($user), 422, 'User not found');

        try {
            $user->bonuses()->update(['used' => true]);

            $data['status'] = true;
            $data['message'] = 'Bonuses marked paid successfully';
        } catch (Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }


        return jsonOrRedirectBack($data);
    }

    public function addBonus(Request $request)
    {
        $user_id = $request->user;
        /* @var $user User */
        $user = User::find($user_id);
        abort_unless(is_object($user), 422, 'User not found');

        try {
            $bonus = new BonusLog;
            $bonus->amount = $request->amount;
            $bonus->name = $request->name;
            $bonus->used = true;
            $bonus->source()->associate($request->user());
            $user->bonuses()->save($bonus);

            $data['status'] = true;
            $data['message'] = 'Bonuses added successfully';
        } catch (Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }
        return jsonOrRedirectBack($data);
    }


    private function getCalculatorDetails()
    {
        $data['plans'] = Plan::all()->keyBy('id');
        $data['coins'] = Exchange::whereIn("symbol", ['BTC', 'ETH', 'BCH'])->get();

        return $data;
    }

    private function validateInvestmentData(User $user, Plan $plan, $amount)
    {
//        if (!$user->canInvest()) {
//            throw new Exception('User still have an ongoing transaction. Try again when all deposits have been verified.');
//        } else
        if (!is_numeric($amount)) {
            throw new Exception('Amount is invalid');
        } elseif (empty($amount)) {
            throw new Exception('No amount specified');
        }

        if ($amount < $plan->minimum) {
            throw new Exception('Amount must be greater than ' . to_currency($plan->minimum) . ' for this plan');
        } elseif ($amount > $plan->maximum) {
            throw new Exception('Amount must not be more than ' . to_currency($plan->maximum) . ' for this plan');
        }
    }

    public function loginAs(Request $request, $user_id)
    {
        $user = User::findOrFail($user_id);
        auth()->login($user);
        return redirect()->route('dashboard.index');
    }
}
