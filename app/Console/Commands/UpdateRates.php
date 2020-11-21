<?php

namespace App\Console\Commands;

use App\Models\Exchange;
use Illuminate\Console\Command;

class UpdateRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update crypto rates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
            'start' => '1',
            'limit' => '100',
            'convert' => 'USD',
        ];

        $headers = [
            'Accepts: application/json',
            'X-CMC_PRO_API_KEY: ' . env('COINMARKETCAP_KEY', '037c35f9-3d4a-4b63-937c-856c9b9982ae')
        ];
        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL


        $curl = curl_init(); // Get cURL resource
        // Set cURL options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,            // set the request URL
            CURLOPT_HTTPHEADER => $headers,     // set the headers
            CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response
        curl_close($curl);

        $data = json_decode($response);
        if (isset($data->status) && !$data->status->error_code)
            $this->updateDataFromCoinCapital($data->data);

    }

    public function updateDataFromCoinCapital(array $rates)
    {
        foreach ($rates as $value) {
            /* @var $exchange Exchange */
            $exchange = Exchange::firstOrNew(['symbol' => $value->symbol]);
            $exchange->key = $value->id;
            $exchange->fill([
                'rank' => $value->cmc_rank,
                'name' => $value->name,
                'symbol' => $value->symbol,
                'price_usd' => $value->quote->USD->price,
                'price_btc' => 0.0
            ]);
            $exchange->save();
        }
    }
}
