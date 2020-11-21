<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Investment;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use const USER_STATUS_SUSPENDED;
use function iResponse;
use function jsonOrRedirectBack;
use function redirect;
use function route;
use function view;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $data['user'] = $user = $request->user();
        $data['ledger'] = $user->ledgerBalance();
//        $data['referrals'] = $user->referrals()->count();
//        $data['bonuses'] = $user->bonuses()
//                        ->selectRaw('(sum(amount) + sum(roi)) as balance')
//                        ->first()->balance;
        $data['deposits'] = $user->investments()->verified()->sum('amount');
        $data['withdrawals'] = $user->withdrawals()->sum('amount');
        $data['balance'] = $user->withdrawableBalance();
//        $data['best_sellers'] = Plan::selectRaw('plans.*, count(plan_id) as count')
//                ->leftJoin('investments', 'plans.id', 'plan_id')
//                ->groupBy('plans.id')
//                ->orderBy('count', 'desc')
//                ->limit(3)
//                ->get();
//        $formData = PaymentController::getInvestmentFormData($request);
//        $data = array_merge($data, $formData);

        $data['meta']['title'] = 'Dashboard';

        return view('dashboard.index', $data);
    }
    
    public function suspended(Request $request)
    {
        if ($request->user()->status !== USER_STATUS_SUSPENDED) {
            return redirect()->route('dashboard.index');
        } else {
            return view('dashboard.suspended');
        }
    }

    public function submitReceipts(Request $request)
    {
        $user = $request->user();
        $investment = $user->investments()
                ->where('reference', $request->reference)
                ->first();
        if (is_object($investment)) {
            if ($request->hasFile('receipt')) {
                $mf = [];
                foreach ($request->file("receipt") as $file) {
                    //process each file
                    $path = $file->storeAs(File::RECEIPTS_DIR . date('Y-W')
                            . '/' . $investment->reference, uniqid()
                            . '.' . $file->extension());
                    if ($path) {
                        $f = new File;
                        $f->path = $path;
                        $mf[] = $f;
                    }
                }
                $investment->paid_at = Carbon::now();
                $investment->save();

                $investment->files()->saveMany($mf);
                $data = [
                    'status' => true,
                    'message' => 'Investment complete! Your proof of payment has '
                    . 'been saved and awaiting verification',
                ];
            } else {
                $data = ['status' => false,
                    'message' => 'No image was attached'];
            }
        } else {
            $data = ['status' => false,
                'message' => 'We couldn\'t find this match'];
        }

        $data['link'] = route('dashboard.investments');
        return jsonOrRedirectBack($data);
    }

    public function viewReceipts(Request $request)
    {
        $investment = Investment::findByReference($request->reference);
        if (is_object($investment)) {
            $user = $request->user();
            $investmentUser = $investment->user;
            if ($user->admin || $investmentUser->id === $user->id) {
                $data = ['status' => true, 'images' => []];
                foreach ($investment->files as $file) {
                    $data['images'][] = $file->getURL();
                }
            } else {
                $data = ['status' => false,
                    'message' => 'Access Denied'];
            }
        } else {
            $data = ['status' => false,
                'message' => 'We couldn\'t find this match'];
        }

        return iResponse('dashboard.receipts', $data);
    }
}
