<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\AffiliateLevel;
use App\Models\BonusLog;
use App\Models\Matrix;
use App\Models\Referral;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function random_chars;
use function view;

class ReferralsController extends Controller
{
    public function index(Request $request)
    {
        $data['user'] = $user = $request->user();
        $r = $user->referrals()
                ->with('user')
                ->latest();
        $r2 = clone $r;
        $data['referrals'] = $r->paginate();
        $data['referralsCount'] = $r2->count();
        $data['used'] = $user->usedReferrals->count();
        $data['confirmed'] = $user->activeReferrals->count();
        $data['pending'] = $user->pendingReferrals->count();

        $this->generateReferralCode($request);

        $data['meta']['title'] = 'Referrals';

        return view('dashboard.referrals', $data);
    }

    public function generateReferralCode(Request $request)
    {
        $user = $request->user();
        if (!$user->ref_code) {
            do {
                $code = random_chars(6); //56,800,235,584 possible combinations. That's fair
                //Find other occurence (case sensitive)
                $code_exist = User::whereRaw("ref_code = '$code' COLLATE utf8_bin")
                        ->count();
            } while ($code_exist); //This shouldn't take long. I guess

            $user->ref_code = $code;
            $user->save();
            return 1;
        } else {
            return 0;
        }
    }

    public function affiliate(Request $request)
    {
        /* @var $user User */
        $data['user'] = $user = $request->user();
        $this->generateReferralCode($request);
        $data['levels'] = AffiliateLevel::all();
        $matrices = Matrix::where(['sponsor_id' => $user->id])
                ->with('user')
                ->with('level')
                ->with('referral.user');
        if ($request->level) {
            $matrices->where('affiliate_level_id', $request->level);
        }
        $data['matrices'] = $matrices->orderBy('affiliate_level_id')->paginate();
        $data['meta']['title'] = 'Super Affiliate Referrals';
        return view('dashboard.affiliate', $data);
    }

    public function resetReferrals(Request $request)
    {
        //Clear bonuses from given date
        //$date = Carbon::parse('2018-07-12 00:00:00');
        $date = Carbon::now()->firstOfYear();
        //Truncate matrix table
        DB::table('matrices')->truncate();
        //Mark referrals after given date as is_used=false
        Referral::where('created_at', '>=', $date)->update(['is_used' => false]);
        BonusLog::where('created_at', '>=', $date)->delete();
        //And allow cron job to rebuild it
        return 'Done';
    }
}
