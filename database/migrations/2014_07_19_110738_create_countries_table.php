<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->unsignedTinyInteger('id', true);
            $table->string('iso', 2);
            $table->string('name');
            $table->string('official_name');
            $table->string('iso3', 3)->nullable();
            $table->string('numcode')->nullable();
            $table->string('phonecode');
            $table->unsignedTinyInteger('relevance')->default(0);
            $table->timestamps();
        });

        //Populate
        $countries = require(storage_path('files/countries.php'));
        $data = [];
        $now = Carbon::now();
        foreach ($countries as $country) {
            $data[] = [
                'iso' => $country[0],
                'official_name' => $country[1],
                'name' => $country[2],
                'iso3' => $country[3],
                'numcode' => $country[4],
                'phonecode' => $country[5],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        DB::table('countries')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }

}
