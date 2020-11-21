<?php

use App\Models\PlanCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanCategoriesTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
                  ['name' => 'Gold', 'description' => ' Plan', 'min_amount' => 200, 'max_amount' => 1000],
                 ['name' => 'Diamond ', 'description' => ' Plan', 'min_amount' => 1000, 'max_amount' => 50000],
                ['name' => 'Platinum', 'description' => ' Plan', 'min_amount' => 50000, 'max_amount' => 10000000],
               
               
                
        ];

        foreach ($categories as $cat) {
            $pc = new PlanCategory;
            foreach ($cat as $attr => $value) {
                $pc->$attr = $value;
            }
            $pc->save();
        }
    }
}
