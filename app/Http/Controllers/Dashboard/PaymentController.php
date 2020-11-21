<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Library\Coinpayment\Traits\CoinpaymentTrait;
use App\Mail\SupportEmail;
use App\Models\Exchange;
use App\Models\Investment;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use function config;
use function env;
use function iResponse;
use function jsonOrRedirectBack;
use function logger;
use function route;
use function setting;
use function to_currency;

class PaymentController extends Controller
{
    use CoinpaymentTrait;

    public function showInvestments(Request $request)
    {
        /* @var $user User */
        $user = $request->user();
        $data['investments'] = $user->investments()
            ->latest()
            ->paginate();
        $data['bonuses'] = $user->bonuses;
        $data['deposits'] = $user->investments()->verified()->sum('amount');
        $formData = self::getInvestmentFormData($request);
        $data = array_merge($data, $formData);
        $data['meta']['title'] = 'Investments';

        return iResponse('dashboard.investments', $data);
    }

    public function showInvestmentPage(Request $request)
    {
        /* @var $user User */
        $user = $request->user();
        $data = self::getInvestmentFormData($request);
        $data['coins'] = Exchange::whereIn("symbol", ['BTC', 'ETH', 'BCH'])->get();
        $data['user'] = $user;
        $data['meta']['title'] = 'Deposit';

        return iResponse('dashboard.invest', $data);
    }

    public static function getInvestmentFormData(Request $request)
    {
        $user = $request->user();
        $data['plans'] = Plan::all()->keyBy('id');
        $data['reinvest'] = !!$request->reinvest;
        $data['balance'] = $user->withdrawableBalance();
        /** @var Investment $incompleteInvestment */
        $incompleteInvestment = $user->investments()->with('exchange')->unpaid()->first();
        $hasIncompleteInvestment = is_object($incompleteInvestment);
        if ($hasIncompleteInvestment) {
            $walletSettingName = strcasecmp($incompleteInvestment->exchange->symbol, 'btc') === 0 ?
                'wallet_address' :
                $incompleteInvestment->exchange->symbol . '_wallet_address';
            $data['wallet'] = setting($walletSettingName);
            $data['coinURI'] = strtolower(preg_replace('/\s+/', '', $incompleteInvestment->exchange->name));

            $coinpayment['amount'] = $incompleteInvestment->btc;
            $plan = $incompleteInvestment->plan;
            $name = "{$plan->name} Plan";
            $coinpayment['description'] = "Payment for $name";
            $coinpayment['reference'] = $incompleteInvestment->reference;
            $data['coinpayment'] = self::getCoinpaymentBtnFormData($coinpayment);
        }
        return array_merge($data, compact('incompleteInvestment', 'hasIncompleteInvestment'));
    }

    public function showWithdrawals(Request $request)
    {
        $user = $request->user();
        $data['withdrawals'] = $user->withdrawals()
            ->latest()
            ->paginate();
        $data['balance'] = $user->withdrawableBalance();
        $data['profileComplete'] = $user->isProfileComplete();

        $data['meta']['title'] = 'Withdrawals';

        return iResponse('dashboard.withdrawals', $data);
    }

    public function markInvestmentAsPaid(Request $request)
    {
        $user = $request->user();
        $investment = $user->investments()
            ->where('reference', $request->reference)
            ->first();
        $investment->paid_at = Carbon::now();
        $investment->save();

        $subject = 'Cash Deposit';
        $message = "Cash deposit of $$investment->amount from {$user->name}";
        $this->notifyAdmin($message, $subject);
        if ($request->_rdr) {
            setting(['wallet_qr_on' => 0]);
        }

        $data = [
            'status' => true,
            'message' => 'Investment complete!',
        ];
        return jsonOrRedirectBack($data);
    }

    public function createInvestment(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $amount = $request->amount;
        try {
//            if (!$user->canInvest()) {
//                throw new Exception('You still have an ongoing transaction. Try again when all deposits have been verified.');
//            } else
            if (!is_numeric($amount)) {
                throw new Exception('Amount is invalid');
            } elseif (empty($amount)) {
                throw new Exception('No amount specified');
            }

            $plan = Plan::find($request->plan);
            if (!is_object($plan)) {
                throw new Exception('No plan specified');
            }
            if ($amount < $plan->minimum) {
                throw new Exception('Amount must be greater than ' . to_currency($plan->minimum) . ' for this plan');
            } elseif ($amount > $plan->maximum) {
                throw new Exception('Amount must not be more than ' . to_currency($plan->maximum) . ' for this plan');
            }


            if ($request->reinvest) {
                $user->reinvest($amount, $plan);

                $subject = 'Cash Re-investment';
                $message = "Cash re-investment of $$amount from {$user->name}";
                $this->notifyAdmin($message, $subject);

                $data['message'] = 'Reinvestment created successfully';
            } else {
                $user->invest($amount, $plan);
                $data['message'] = 'Request created successfully';
            }

            $data['status'] = true;
        } catch (Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }


        return jsonOrRedirectBack($data);
    }

    public function createWithdrawal(Request $request)
    {
        $user = $request->user();
        if (!$user->isProfileComplete()) {
            $data['status'] = false;
            $data['link'] = route('dashboard.profile');
            $data['message'] = 'Please complete your profile first';
        } elseif ($user->withdrawal_frozen) {
            $data['status'] = false;
            $data['message'] = 'You cannot make withdrawals at the moment';
        } else {
            try {
                $amount = $request->input('amount');
                if (!is_numeric($amount) || $amount < 0) {
                    throw new Exception('Invalid amount');
                }
                $balance = $user->withdrawableBalance();
                if ($amount > $balance) {
                    throw new Exception('You can not withdraw more than '
                        . to_currency($balance));
                }
                $user->withdraw($amount);

                $subject = 'Cash Withdrawal';
                $message = "Cash withdrawal request of $$amount from {$user->name}";
                $this->notifyAdmin($message, $subject);

                $data['status'] = true;
                $data['message'] = 'Request created successfully';
            } catch (Exception $e) {
                $data['status'] = false;
                $data['message'] = $e->getMessage();
            }
        }

        return jsonOrRedirectBack($data);
    }

    public function cancelInvestmentRequest(Request $request)
    {
        $user = $request->user();
        $investment = $user->investments()
            ->where('reference', $request->reference)
            ->first();
        if (is_object($investment)) {
            $investment->delete();
            $data = ['status' => true, 'message' => 'Request cancelled'];
        } else {
            $data = ['status' => false, 'message' => 'We could not find this investment request'];
        }
        return jsonOrRedirectBack($data);
    }

    public function cancelWithdrawalRequest(Request $request)
    {
        $user = $request->user();
        $wr = $user->withdrawals()
            ->where('reference', $request->reference)
            ->first();
        try {
            if (!is_object($wr)) {
                throw new Exception('Request failed, we could not fetch this withdrawal request');
            } elseif (!empty($wr->approved_at)) {
                throw new Exception('Approved requests cannot be cancelled');
            }

            $wr->delete();
            $data['status'] = true;
            $data['message'] = 'Request cancelled';
        } catch (Exception $e) {
            $data['status'] = false;
            $data['message'] = $e->getMessage();
        }
        return jsonOrRedirectBack($data);
    }

    public function notifyAdmin($message, $subject)
    {
        $input['message'] = $message;
        $input['subject'] = $subject;
        $input['name'] = config('app.name');
        $input['email'] = 'no-reply@' . env('APP_DOMAIN');
        $input['forwarded_to'] = setting('support_email', 'support@' . env('APP_DOMAIN'));
        try {
            Mail::to($input['forwarded_to'], config('app.name') . ' Support')
                ->send(new SupportEmail($input));
        } catch (Exception $exc) {
            logger($exc);
        }
    }
}
