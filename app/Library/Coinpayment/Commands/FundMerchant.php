<?php

namespace App\Library\Coinpayment\Commands;

use App\Library\Coinpayment\Models\CoinpaymentTrxLog;
use App\Library\Coinpayment\Traits\CoinpaymentTrait;
use App\Models\Exchange;
use Exception;
use Illuminate\Console\Command;
use function config;
use function logger;
use function setting;
use function url;

class FundMerchant extends Command
{
    use CoinpaymentTrait;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'coinpayment:fund-merchant';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fund merchant';

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
        $logs = CoinpaymentTrxLog::where('status', '>=', 100)
                ->where('wire_status', -100)->get();
        foreach ($logs as $log) {
            $this->transfer($log);
        }
    }
    
    
    public function transfer(CoinpaymentTrxLog $log)
    {
        //Transfer
        $paid = $log->amount;
        $coinpaymentRate = config('coinpayment.rate', 0.005);
        $coinpaymentAppRate = config('coinpayment.app_rate', 0.05);
        $coinpaymentCharge = $paid * $coinpaymentRate;
        $appServiceCharge = $paid * $coinpaymentAppRate;
        $amount = $paid - $coinpaymentCharge - $appServiceCharge;

        $wire_address = setting('wallet_address');
        $wire_currency = 'BTC';
        $client_id = setting('COIN_PAYMENT_CLIENT_ID');
        $merchant_id = setting('COIN_PAYMENT_MERCHANT_ID');
        if (strcasecmp($client_id, $merchant_id) !== 0) {
            $wire_address = $client_id;
            $response = $this->api_call('create_transfer', [
                'amount' => $amount,
                'currency' => $wire_currency,
                'merchant' => $client_id,
                'auto_confirm' => 1
            ]);
        } else {
            $response = $this->api_call('create_withdrawal', [
                'amount' => $amount,
                'currency' => $wire_currency,
                'currency2' => $log->coin,
                'address' => $wire_address,
                'ipn_url' => url('coinpayment/ipn'),
                'auto_confirm' => 1,
                'add_tx_fee' => 1
            ]);
        }

        if ($response['error'] == 'ok') {
            $log->wire_amount = $response['result']['amount']??$amount;
            $log->wire_id = $response['result']['id'];
            $log->wire_status = $response['result']['status'];
            $log->wire_currency = $wire_currency;
            $log->wire_address = $wire_address;
            $log->save();
        } else {
            $log->wire_status = -1;
            $log->wire_address = $response['error'];
            $log->save();
            logger("Transaction Log ($log->id): {$response['error']}");
        }
    }

}
