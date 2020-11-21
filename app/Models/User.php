<?php

namespace App\Models;

use App\Library\Coinpayment\Models\UsesCoinpayment;
use App\Models\Traits\HasPhoto;
use App\Models\Traits\HasSlug;
use App\Models\Traits\ModelHelpers;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use function to_bitcoin;
use function to_crypto;
use const USER_STATUS_ACTIVE;
use const USER_STATUS_SUSPENDED;

class User extends Authenticatable
{
    use Notifiable;
    use ModelHelpers;
    use SoftDeletes;
    use HasPhoto;
    use HasSlug;
    use UsesCoinpayment;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'first_name', 'last_name', 'phone', 'slug', 'email', 'password', 'p_plain', 'preferences',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'photo',
    ];

    /**
     *
     * @var array
     */
    protected $casts = ['preferences' => 'array'];


    protected $balance;
    protected $ledgerBalance;
    protected $totalBonus;
    protected $paidBonus;
    protected $unpaidBonus;

    /**
     * @param $email
     *
     * @return mixed
     */
    public static function findByEmail($email)
    {
        return self::findByColumn('email', $email)->first();
    }

    /**
     * @param $phone
     *
     * @return mixed
     */
    public static function findByPhone($phone)
    {
        return self::findByColumn('phone', $phone)->first();
    }

    /**
     * @return array
     */
    public static function getDefaultPreferences()
    {
        return [
            'notifications' => [
                'email' => true,
                'sms' => true
            ]
        ];
    }

    public function getFirstNameAttribute($name)
    {
        return ucfirst($name);
    }

    public function getLastNameAttribute($name)
    {
        return ucfirst($name);
    }

    public function getNameAttribute($name)
    {
        return ucwords($this->first_name . ' ' . $this->last_name);
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }

    public function activeReferrals()
    {
        return $this->hasMany(Referral::class)->where('is_confirmed', 1)
            ->where('is_used', 0);
    }

    public function confirmedReferrals()
    {
        return $this->hasMany(Referral::class)->where('is_confirmed', 1);
    }

    public function usedReferrals()
    {
        return $this->hasMany(Referral::class)->where('is_used', 1);
    }

    public function pendingReferrals()
    {
        return $this->hasMany(Referral::class)->where('is_confirmed', 0);
    }

    public function referredBy()
    {
        return $this->hasOne(Referral::class, 'referred');
    }

    public function bonuses()
    {
        return $this->hasMany(BonusLog::class);
    }

    public function suspend($reason = '')
    {
        $this->status = USER_STATUS_SUSPENDED;
        $this->suspended_for = $reason;
        $this->save();
    }

    public function isSuspended()
    {
        return $this->status === USER_STATUS_SUSPENDED;
    }

    public function activate()
    {
        $this->status = USER_STATUS_ACTIVE;
        $this->save();
    }

    public function isProfileComplete()
    {
        return $this->name &&
            $this->email &&
//                $this->phone &&
//                $this->address &&
//                $this->identity_photo &&
//                $this->photo &&
            $this->wallet_id;
    }

    /**
     * Create use request for this user
     * @param type $amount
     * @return type
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function withdraw($amount, $verified = false)
    {
        $request = new Withdrawal;
        $request->amount = $amount;
        $request->btc = to_bitcoin($amount);
        if ($verified) {
            $request->paid_at = Carbon::now();
            $request->approved_at = Carbon::now();
        }
        return $this->withdrawals()->save($request);
    }

    /**
     * Create investment request for this user
     * @param integer $amount
     * @param Plan $plan
     * @param bool $verified
     * @return boolean
     */
    public function invest($amount, Plan $plan, $verified = false)
    {
        $investment = new Investment;
        $investment->amount = $amount;

//        $investment->btc = to_bitcoin($amount);
        $exchange_id = request('coin', 1);
        $investment->btc = to_crypto($amount, $exchange_id);
        $investment->exchange_id = $exchange_id;

        $investment->plan_id = $plan->id;
        $investment->daily_rate = $plan->getDailyRate($amount);
        $investment->due_at = Carbon::now()->addDays($plan->incubation);
        $investment->expire_at = Carbon::now()->addDays($plan->duration);
        if ($verified) {
            $investment->paid_at = Carbon::now();
            $investment->verified_at = Carbon::now();
        }
        return $this->investments()->save($investment);
    }

    /**
     * Create re-investment for this user
     * @param type $amount
     * @return type
     * @throws Exception
     */
    public function reinvest($amount, Plan $plan)
    {
        $balance = $this->withdrawableBalance();
        if ($amount > $balance) {
            throw new \Exception("Withdrawable balance ${$balance} is not up to ${$amount}");
        }

        return DB::transaction(function () use ($amount, $plan) {
            $withdrawal = new Withdrawal;
            $withdrawal->amount = $amount;
            $withdrawal->btc = to_bitcoin($amount);
            $now = Carbon::now();
            $withdrawal->paid_at = $now;
            $withdrawal->approved_at = $now;
            $this->withdrawals()->save($withdrawal);

            $investment = new Investment;
            $investment->amount = $amount;
            $investment->btc = to_bitcoin($amount);
            $investment->exchange_id = 1;
            $investment->plan_id = $plan->id;
            $investment->daily_rate = $plan->getDailyRate($amount);
            $investment->paid_at = $now;
            $investment->verified_at = $now;
            $investment->due_at = Carbon::now()->addDays($plan->incubation);
            $investment->expire_at = Carbon::now()->addDays($plan->duration);
            return $this->investments()->save($investment);
        });
    }

    public function suggestReinvestPlan()
    {
        return Plan::where('minimum', '<=', $this->withdrawableBalance())
            ->orderBy('minimum', 'DESC')
            ->first();
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function investments()
    {
        return $this->hasMany(Investment::class);
    }

    public function affiliateLevel()
    {
        return $this->belongsTo(AffiliateLevel::class, 'affiliate_level_id');
    }

    public function withdrawableBalance()
    {
        if (!$this->balance) {
            $amount = $this->investments()
                ->verified()
                ->expired()
                ->sum('amount');
            $roi = $this->investments()
                ->verified()
                ->sum('roi');
//            $bonus = $this->unpaidBonus();
            $withdrawn = $this->withdrawals()->sum('amount');
//            $this->balance = $amount + $roi + $bonus - $withdrawn;
            $this->balance = $amount + $roi - $withdrawn;
        }
        return $this->balance;
    }

    public function ledgerBalance()
    {
        if (!$this->ledgerBalance) {
            $amount = $this->investments()
                ->verified()
                ->sum('amount');
            $roi = $this->investments()
                ->verified()
                ->sum('roi');
//            $bonus = $this->unpaidBonus();
            $withdrawn = $this->withdrawals()->sum('amount');
//            $this->ledgerBalance = $amount + $roi + $bonus - $withdrawn;
            $this->ledgerBalance = $amount + $roi - $withdrawn;
        }
        return $this->ledgerBalance;
    }

    public function totalBonus()
    {
        if (!$this->totalBonus) {
            $this->totalBonus = $this->bonuses()
                ->selectRaw('(sum(amount) + sum(roi)) as balance')
                ->first()->balance;
        }

        return $this->totalBonus;
    }

    public function paidBonus()
    {
        if (!$this->paidBonus) {
            $this->paidBonus = $this->bonuses()
                ->used()
                ->selectRaw('(sum(amount) + sum(roi)) as balance')
                ->first()->balance;
        }

        return $this->paidBonus;
    }

    public function unpaidBonus()
    {
        if (!$this->unpaidBonus) {
            $this->unpaidBonus = $this->bonuses()
                ->unused()
                ->selectRaw('(sum(amount) + sum(roi)) as balance')
                ->first()->balance;
        }

        return $this->unpaidBonus;
    }

    public function hasPendingInvestment()
    {
        return $this->investments()
                ->unverified()
                ->count() > 0;
    }

    public function canInvest()
    {
        return !$this->hasPendingInvestment();
    }
}
