<?php

use Illuminate\Database\Seeder;

class ExchangeSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->isLocal()) {
            App\Models\Exchange::reguard();
            App\Models\Exchange::updateData([
                [
                    'id' => 'bitcoin',
                    'rank' => 1,
                    'name' => 'Bitcoin',
                    'symbol' => 'BTC',
                    'price_usd' => 4500.374039,
                    'price_btc' => 1.0
                ]
            ]);
        }
    }

}
