<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinpaymentTrxLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coinpayment_trx_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('payment_id');
            $table->string('reference');
            $table->string('payment_address');
            $table->double('amount', 20, 8);
            $table->string('coin', 10)->nullable();
            $table->string('fiat', 10)->nullable();
            $table->string('status_text')->nullable();
            $table->integer('status')->default(0);
            $table->datetime('payment_created_at')->nullable();
            $table->datetime('expired')->nullable();
            $table->datetime('confirmation_at')->nullable();
            $table->integer('confirms_needed')->default(0);
            $table->string('qrcode_url')->nullable();
            $table->string('status_url')->nullable();
            $table->text('payload')->nullable();
            $table->string('wire_currency')->nullable();
            $table->double('wire_amount', 20, 8)->nullable();
            $table->string('wire_id')->nullable();
            $table->string('wire_address')->nullable();
            $table->integer('wire_status')->default(-100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coinpayment_trx_logs');
    }
}
