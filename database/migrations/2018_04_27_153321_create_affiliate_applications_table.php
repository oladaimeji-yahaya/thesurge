<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliate_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('location');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->text('message');
            $table->string('forwarded_to');
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
        Schema::dropIfExists('affiliate_applications');
    }
}
