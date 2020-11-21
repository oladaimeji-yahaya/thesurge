<?php

namespace App\Console\Commands;

use App\Models\AffiliateLevel;
use App\Models\BonusLog;
use App\Models\Investment;
use App\Models\Matrix;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use function setting;

class CreditReferrals extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credit:referrals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Credit referral bonus';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $referrals = Referral::where(['is_used' => 0])
                ->with('referredUser')
                ->with('user')
                ->get();
        foreach ($referrals as $referral) {
            if (!$this->validateReferral($referral)) {
                continue;
            }

            DB::transaction(function () use ($referral) {
                $this->creditReferrers($referral);
            });
        }
    }

    private function validateReferral(Referral $referral)
    {
        $referred = $referral->referredUser;
        if (!is_object($referred)) {
            return false;
        }

        $referrer = $referral->user;
        //Skip if no referrer
        if (!is_object($referrer)) {
            return false;
        }

        //Get referral first verified payment
        $investment = $referred->investments()->verified()->first();
        //Skip if no payment
        if (!is_object($investment)) {
            return false;
        }

        return true;
    }

    private function creditReferrers(Referral $referral)
    {
        /* @var $referred User */
        $referred = $referral->referredUser;
        //Get referral first verified payment
        $investment = $referred->investments()->verified()->first();

        //credit all sponsors
        $levels = AffiliateLevel::all();
        $count = 0;
        $maxIndex = count($levels) - 1;


        /* @var $child User */
        $currentRef = $referral;
        do {
            /** @var User $sponsor */
            $sponsor = $currentRef->user()->with('referredBy')->first();

            $level = $levels->get($count);
            $this->creditSuperAffiliate($investment, $sponsor, $referred, $level, $referral);
            //Maintain last level for all sponsors greater than level
            $count = min([++$count, $maxIndex]);
            //Next sponsor
            $currentRef = $sponsor->referredBy;
        } while (is_object($currentRef));

        $referral->is_used = true;
        $referral->save();
    }

    private function creditSuperAffiliate(Investment $investment, User $sponsor, User $referred, AffiliateLevel $level, Referral $referral)
    {
        //build tree, returns $matrix;
        $matrix = $this->buildTree($investment, $sponsor, $referred, $level, $referral);
        $pay = false;
        if ($sponsor->super_affiliate) {
            $matrices = Matrix::where([
                        'sponsor_id' => $sponsor->id,
                        'affiliate_level_id' => $level->id,
                        'released' => false
                    ])
                    ->with('referral.referredUser')
                    ->get();
            //Check if legs are complete
            $pay = count($matrices) % $level->referrals === 0;
        } elseif ($level->rank === 1) {
            //If not super affiliate and matrix on level one
            //pay immediately
            $matrices = [$matrix];
            $pay = true;
        }

        if ($pay) {
            //add bonuses, mark all released and upgrade user
            foreach ($matrices as $matrix) {
                $this->creditBonus($matrix, $sponsor);
                $this->upgradeSponsor($sponsor, $level);
            }
        }
    }

    private function buildTree(Investment $investment, User $sponsor, User $referred, AffiliateLevel $level, Referral $referral)
    {
        //If tree does not exist, build tree
        /* @var $matrix Matrix */
        $matrix = Matrix::firstOrNew([
                    'user_id' => $referred->id,
                    'sponsor_id' => $sponsor->id,
                    'affiliate_level_id' => $level->id
        ]);

        if ($matrix->exists) {
            return $matrix;
        }

        $this->updatePosition($matrix, $referral);
        $this->updatePercentage($matrix, $sponsor, $level);
        $this->updateAmount($matrix, $investment);
        $matrix->referral()->associate($referral);
        $matrix->investment()->associate($investment);
        $matrix->save();

        return $matrix;
    }

    private function updatePosition(Matrix $matrix, Referral $referral)
    {
        $parent = $referral->user;
        $position = 'Left';
        for ($i = 1; $i <= count($parent->referrals); $i++) {
            $ref = $parent->referrals->get($i - 1);
            if ($ref->id === $referral->id) {
                $position = $i % 2 === 0 ? 'Right' : 'Left';
                break;
            }
        }

        $matrix->position = $position;
    }

    private function updateAmount(Matrix $matrix, $investment)
    {
        $matrix->amount = $investment->amount * ($matrix->percentage / 100);
    }

    private function updatePercentage(Matrix $matrix, User $sponsor, AffiliateLevel $level)
    {
        if ($sponsor->super_affiliate) {
            $matrix->percentage = $level->bonus;
        } else {
            $matrix->percentage = $level->rank == 1 ? setting('ref_bonus', 10) : 0;
        }
    }

    private function creditBonus(Matrix $matrix, User $sponsor)
    {
        if (!$matrix->amount) {
            return;
        }

        $bonus = new BonusLog;
        $bonus->amount = $matrix->amount;
        $referred = $matrix->referral->referredUser;
        $referredName = is_object($referred) ? $referred->name : 'Deleted User';
        $bonus->name = "Referral bonus for {$referredName}";
        $bonus->user()->associate($sponsor);
        $bonus->source()->associate($matrix);
        $sponsor->bonuses()->save($bonus);

        $matrix->released = true;
        $matrix->save();

        //Record earnings on this referral
        $referral = $matrix->referral()->with('user')->first();
        if ($sponsor->id == $referral->user->id) {
            $referral->is_confirmed += $matrix->amount;
            $referral->save();
        }
    }

    private function upgradeSponsor(User $sponsor, AffiliateLevel $currentLevel)
    {
        $max = AffiliateLevel::max('rank');
        $nextLevel = min([$currentLevel->rank + 1, $max]);
        $sponsorLevel = $sponsor->affiliateLevel;
        if ($nextLevel > $sponsorLevel->rank) {
            //Notify user if needed here
            $newLevel = AffiliateLevel::where('rank', $nextLevel)->first();
            $sponsor->affiliateLevel()->associate($newLevel);
            $sponsor->save();
        }
    }
}
