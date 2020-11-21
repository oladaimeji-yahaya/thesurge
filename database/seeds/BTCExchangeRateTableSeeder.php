<?php

use App\Models\BTCExchangeRate;
use Illuminate\Database\Seeder;

class BTCExchangeRateTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rates = getBTCRates();
        BTCExchangeRate::updateData($rates);
    }

}
