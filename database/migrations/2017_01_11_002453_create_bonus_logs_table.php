<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBonusLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bonus_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference')->nullable();
            $table->integer('user_id', false, true);
            $table->string('name')->default('Referral Bonus');
            $table->integer('amount', false, true)->nullable();
            $table->decimal('roi')->default(0);
            $table->morphs('source');
            $table->timestamp('due_at')->nullable();
            $table->boolean('used')->default(0);
            $table->string('extra')->nullable();
            $table->timestamps();

            $table->foreign('user_id', 'bonus_logs_user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bonus_logs');
    }
}
