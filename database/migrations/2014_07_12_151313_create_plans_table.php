<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->unsignedSmallInteger('id', true);
            $table->string('name');
            $table->decimal('rate');
            $table->unsignedSmallInteger('incubation');
            $table->decimal('minimum')->default(1000);
            $table->decimal('maximum', 12)->default(1000000000);
            $table->decimal('compounding')->default(500); //6 months
            $table->unsignedInteger('duration')->default(180); //6 months
            $table->string('description')->nullable();
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
        Schema::dropIfExists('plans');
    }
}
