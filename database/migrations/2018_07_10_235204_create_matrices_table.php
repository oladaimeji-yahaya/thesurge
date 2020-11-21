<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matrices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('sponsor_id');
            $table->unsignedTinyInteger('affiliate_level_id');
            $table->string('position');
            $table->unsignedInteger('investment_id');
            $table->decimal('amount', 12, 2);
            $table->decimal('percentage', 5);
            $table->unsignedInteger('referral_id');
            $table->boolean('released')->default(false);
            $table->timestamps();
            
            
            $table->foreign('user_id', 'matrices_user_id')
                    ->references('id')->on('users')
                    ->onUpdate('cascade');
            $table->foreign('sponsor_id', 'matrices_sponsor_id')
                    ->references('id')->on('users')
                    ->onUpdate('cascade');
            $table->foreign('affiliate_level_id', 'matrices_affiliate_level_id')
                    ->references('id')->on('affiliate_levels')
                    ->onUpdate('cascade');
            $table->foreign('investment_id', 'matrices_investment_id')
                    ->references('id')->on('investments')
                    ->onUpdate('cascade');
            $table->foreign('referral_id', 'matrices_referral_id')
                    ->references('id')->on('referrals')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matrices');
    }
}
