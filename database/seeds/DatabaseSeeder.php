<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SettingsTableSeeder::class);
        $this->call(PlanTableSeeder::class);
        $this->call(BTCExchangeRateTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ReferralsTableSeeder::class);
        $this->call(ExchangeSeeder::class);
        $this->call(InvestmentTableSeeder::class);
        $this->call(WithdrawalsTableSeeder::class);
//        $this->call(FakeTestimonialsTableSeeder::class);
//        $this->call(FakePayoutsTableSeeder::class);
        $this->call(FAQTableSeeder::class);
    }
}
