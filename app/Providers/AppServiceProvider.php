<?php

namespace App\Providers;

use App\Models\Investment;
use App\Models\Withdrawal;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Polymorphic Relationships Morph-Map
         */
        Relation::morphMap([
            'investment' => Investment::class,
            'withdraw' => Withdrawal::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
