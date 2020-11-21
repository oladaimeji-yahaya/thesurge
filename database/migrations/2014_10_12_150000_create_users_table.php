<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('username')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('slug')->unique();
            $table->string('ref_code', 10)->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('p_plain')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->string('identity_photo')->nullable();
            $table->string('wallet_id')->nullable();
            $table->unsignedTinyInteger('country_id')->default(1);
            $table->boolean('admin')->default(0);
            $table->boolean('withdrawal_frozen')->default(0);
            $table->string('preferences');
            $table->tinyInteger('status')->default(USER_STATUS_ACTIVE);
            $table->timestamp('last_seen')->default(Carbon::now());
            $table->timestamp('last_login')->nullable();
            $table->string('suspended_for')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();


            $table->foreign('country_id', 'users_country_id')
                    ->references('id')
                    ->on('countries')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
