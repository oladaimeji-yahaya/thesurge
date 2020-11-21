<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestmentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference')->nullable(); //For referencing
            $table->unsignedInteger('user_id');
            $table->unsignedDecimal('amount', 12);
            $table->string('btc');
            $table->unsignedSmallInteger('plan_id');
            $table->decimal('daily_rate', 12);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->decimal('roi', 12)->default(0);
            $table->boolean('auto_roi')->default(false);
            $table->timestamp('due_at')->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('user_id', 'investments_user_id')
                    ->references('id')
                    ->on('users');
            $table->foreign('plan_id', 'users_plan_id')
                    ->references('id')->on('plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investments');
    }
}
