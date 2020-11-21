<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFakePayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fake_payouts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('country')->nullable();
            $table->string('amount');
            $table->string('confirmations')->nullable();
            $table->string('address');
            $table->string('txid')->nullable();
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
        Schema::dropIfExists('fake_payouts');
    }
}
