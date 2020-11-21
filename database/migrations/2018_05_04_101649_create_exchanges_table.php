<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rank');
            $table->string('key')->unique();
            $table->string('name');
            $table->string('symbol');
            $table->string('price_usd');
            $table->string('price_btc');
            $table->text('pay_to')->nullable(); //Address
            $table->text('qr')->nullable();
            $table->text('qr_data')->nullable();
            $table->text('qr_text')->nullable();
            $table->boolean('enabled')->default(false);
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
        Schema::dropIfExists('exchanges');
    }
}
