<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExchangeToInvestment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('investments', function (Blueprint $table) {
            $table->unsignedInteger('exchange_id')->nullable()->default(1)->after('btc');
            
            $table->foreign('exchange_id', 'investments_exchange_id')
                    ->references('id')->on('exchanges')
                    ->onDelete('set null')
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
        Schema::table('investments', function (Blueprint $table) {
            $table->dropForeign('investments_exchange_id');
            $table->dropColumn('exchange_id');
        });
    }
}
