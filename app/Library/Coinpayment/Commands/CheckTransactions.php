<?php

namespace App\Library\Coinpayment\Commands;

use App\Library\Coinpayment\Jobs\CallbackProcessJob;
use App\Library\Coinpayment\Models\CoinpaymentTrxLog;
use App\Library\Coinpayment\Traits\CoinpaymentTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use function dispatch;
use function json_decode;

class CheckTransactions extends Command
{
    use CoinpaymentTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'coinpayment:transaction-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking transaction refund';

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

        // Parameter hook
        $this->info('======================= CHECKING STARTING =======================');
        CoinpaymentTrxLog::whereIn('status', [0, 1])->where('expired', '>', Carbon::now())
                ->chunk(100, function ($transactions) {
                    foreach ($transactions as $trx) {
                        $this->async_proccess($trx, function ($check) use ($trx) {
                            if ($check['error'] == 'ok') {
                                $data = $check['result'];
                                $this->info('Address: ' . $data['payment_address']);
                                if ($data['status'] > 0 || $data['status'] < 0) {
                                    $trx->update([
                                        'status_text' => $data['status_text'],
                                        'status' => $data['status'],
                                        'payment_address' => $data['payment_address'],
                                        'confirmation_at' => ((INT) $data['status'] === 100) ? date('Y-m-d H:i:s', $data['time_completed']) : null
                                    ]);

                                    $this->info('Status : ' . $data['status_text']);

                                    // Send hook
                                    $this->info('======================= SENDING WEBHOOK =======================');
                                    $data['payload'] = (Array) json_decode($trx->payload, true);
                                    $data['request_type'] = 'schedule_transaction';
                                    $data['txn_id'] = $trx->payment_id;
                                    dispatch(new CallbackProcessJob($data));
                                }
                            }
                        });
                    }
                });
    }

    private function async_proccess($trx, $callback)
    {
        $check = $this->api_call('get_tx_info', [
                    'txid' => $trx->payment_id
        ]);
        return $callback($check);
    }
}
