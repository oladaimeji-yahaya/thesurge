<?php

use Illuminate\Database\Seeder;

class FakePayoutsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = Carbon\Carbon::now();
        $data = [];
        $bit_addresses = file(storage_path('files/bit_addresses'));
        $bit_amounts = file(storage_path('files/bit_amounts'));
        if (count($bit_addresses) !== count($bit_amounts)) {
            throw new \Exception('Bitcoin address and amount list does not match');
        }
        $faker = Faker\Factory::create();
        for ($i = 0; $i < count($bit_addresses); $i++) {
            $bit_address = trim($bit_addresses[$i]);
            $bit_amount = trim(str_replace('BTC', '', $bit_amounts[$i]));

            $data[] = [
                'username' => $faker->userName,
                'country' => $faker->country,
                'amount' => $bit_amount,
                'confirmations' => $faker->numberBetween(1, 3),
                'address' => $bit_address,
                'txid' => $faker->bothify('??#??#??#?????#?#??##????????????#??#????#?#??????#??????#'),
                'created_at' => $time,
                'updated_at' => $time,
            ];
        }

        \App\Models\FakePayouts::insert($data);
    }
}
