<?php

use Illuminate\Database\Seeder;

class FAQTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = Carbon\Carbon::now();
        $data = [
                ['question' => 'Who can join ' . config('app.name') . '?',
                'answer' => "Everybody and anybody.\nWe contribute to the common good. "
                . "The participants are asked only to follow the recommendations and avoid penalties.",
                'created_at' => $time,
                'updated_at' => $time],
                ['question' => 'How to participate safely',
                'answer' => "The system on it's own track and monitor suspicious and fraudulent activities."
                . "It monitors the integrity of participants and if your guilt is proven, you will be excluded from Apex Coins. "
                . "That may be cool but we cannot do it all on our own, on your part, ensure that you do not share you credentials such as, "
                . "credit card details and passwords with someone else. Note that the Apex Coins Team will never ask for them.",
                'created_at' => $time,
                'updated_at' => $time],
                ['question' => 'How much money can I raise?',
                'answer' => "We offer unlimited income potential. Many of our members earn thousands very quickly. "
                . "We cannot, however, make income projections for you.\n"
                . "This will be due to your efforts, perseverance and marketing consistency. "
                . "This is the most powerful automated system online today and was created for novices online as well as advanced marketers. "
                . "The only income limits are ones that you make on yourself.",
                'created_at' => $time,
                'updated_at' => $time],
        ];
        \App\Models\FAQ::insert($data);
    }
}
