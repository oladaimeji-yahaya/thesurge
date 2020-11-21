<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateLevelsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_levels', function (Blueprint $table) {
            $table->unsignedTinyInteger('id', true);
            $table->string('name');
            $table->unsignedTinyInteger('rank');
            $table->unsignedTinyInteger('referrals');
            $table->decimal('bonus', 4, 2);
            $table->timestamps();
        });

        $now = Carbon::now();
        DB::table('affiliate_levels')->insert([
                ['name' => 'Level 1', 'rank' => 1, 'referrals' => 2, 'bonus' => 17, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Level 2', 'rank' => 2, 'referrals' => 4, 'bonus' => 5, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Level 3', 'rank' => 3, 'referrals' => 8, 'bonus' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Level 4', 'rank' => 4, 'referrals' => 18, 'bonus' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Level 5', 'rank' => 5, 'referrals' => 32, 'bonus' => 0.5, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Level 6', 'rank' => 6, 'referrals' => 48, 'bonus' => 0.3, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Level 7', 'rank' => 7, 'referrals' => 64, 'bonus' => 0.1, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Level 8', 'rank' => 8, 'referrals' => 200, 'bonus' => 0.01, 'created_at' => $now, 'updated_at' => $now],
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->decimal('balance')->after('wallet_id')->default(0);
            $table->boolean('super_affiliate')->default(0)->after('admin');
            $table->unsignedTinyInteger('affiliate_level_id')->after('super_affiliate')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('balance');
            $table->dropColumn('super_affiliate');
            $table->dropColumn('affiliate_level_id');
        });

        Schema::dropIfExists('affiliate_levels');
    }
}
