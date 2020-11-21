<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBtcExchangeRates extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('btc_exchange_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('currency', 3);
            $table->string('_15m');
            $table->string('last');
            $table->string('buy');
            $table->string('sell');
            $table->string('symbol');
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
        Schema::dropIfExists('btc_exchange_rates');
    }

}
