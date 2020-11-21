<?php

namespace App\Library\Coinpayment\Traits;

use App\Library\Coinpayment\Jobs\CallbackProcessJob;
use App\Library\Coinpayment\Jobs\IPNHandlerJob;
use App\Library\Coinpayment\Models\CoinpaymentTrxLog;
use App\Library\Coinpayment\Resources\TransactionResourceCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function auth;
use function config;
use function dispatch;
use function json_decode;
use function json_encode;
use function logger;
use function response;
use function view;

trait CoinpaymentControllerTrait
{
    use CoinpaymentTrait;

    public function index($serialize)
    {
        $data['data'] = $this->get_payload($serialize);
        $data['params'] = empty($data['data']['params']) ? json_encode([]) : json_encode($data['data']['params']);
        $data['payload'] = empty($data['data']['payload']) ? json_encode([]) : json_encode($data['data']['payload']);
        return view('coinpayment::index', $data);
    }

    public function ajax_rates(Request $req, $usd)
    {
        $coins = [];
        $aliases = [];
        $rates = $this->api_call('rates', ['accepted' => 1])['result'];

        $rateBtc = $rates['BTC']['rate_btc'];
        $rateUsd = $rates[config('coinpayment.default_currency')]['rate_btc'];
        $rateAmount = $rateUsd * $usd;
        $fiat = [];
        $coins_accept = [];
        foreach ($rates as $i => $coin) {
            if ((INT) $coin['is_fiat'] === 0) {
                $rate = ($rateAmount / $rates[$i]['rate_btc']);
                $coins[] = [
                    'name' => $coin['name'],
                    'rate' => number_format($rate, 8, '.', ''),
                    'iso' => $i,
                    'icon' => 'https://www.coinpayments.net/images/coins/' . $i . '.png',
                    'selected' => $i == 'BTC' ? true : false,
                    'accepted' => $coin['accepted']
                ];

                $aliases[$i] = $coin['name'];
            }

            if ((INT) $coin['is_fiat'] === 0 && $coin['accepted'] == 1) {
                $rate = ($rateAmount / $rates[$i]['rate_btc']);
                $coins_accept[] = [
                    'name' => $coin['name'],
                    'rate' => number_format($rate, 8, '.', ''),
                    'iso' => $i,
                    'icon' => 'https://www.coinpayments.net/images/coins/' . $i . '.png',
                    'selected' => $i == 'BTC' ? true : false,
                    'accepted' => $coin['accepted']
                ];
            }


            if ((INT) $coin['is_fiat'] === 1) {
                $fiat[$i] = $coin;
            }
        }

        return response()->json([
                    'coins' => $coins,
                    'coins_accept' => $coins_accept,
                    'aliases' => $aliases,
                    'fiats' => $fiat
        ]);
    }

    public function make_transaction(Request $req)
    {
        $err = $req->validate([
            'amount' => 'required|numeric',
            'payment_method' => 'required'
        ]);

        if (!empty($err['message'])) {
            return response()->json($err);
        }

        $params = [
            'amount' => $req->amount,
            'currency1' => config('coinpayment.default_currency'),
            'currency2' => $req->payment_method,
        ];

        return $this->api_call('create_transaction', $params);
    }

    public function trx_info(Request $req)
    {
        $payment = $this->update_transaction($req->result['txn_id'], $req);

        $send['request_type'] = 'create_transaction';
        $send['params'] = empty($req->params) ? [] : $req->params;
        $send['payload'] = empty($req->payload) ? [] : $req->payload;
        $send['transaction'] = $payment['error'] == 'ok' ? $payment['result'] : [];
        dispatch(new CallbackProcessJob($send));
        return $payment;
    }

    public function update_transaction($txn_id, Request $req = null)
    {
        $payment = $this->api_call('get_tx_info', [
            'txid' => $txn_id
        ]);

        if ($payment['error'] == 'ok') {
            $payment['result']['txn_id'] = $txn_id;
            $this->create_transaction_record($payment['result'], $req);
        }

        return $payment;
    }

    private function create_transaction_record($data, Request $req = null)
    {
        $user = auth()->user();
        if (!is_object($user)) {
            $invest_Class = config('coinpayment.investment_table');
            if (method_exists($invest_Class, 'findByReference')) {
                $investment = $invest_Class::findByReference($data['item_number'] ?? '');
                $user = is_object($investment) ? $investment->user : null;
            }
        }

        //If no user of transaction already exists
        if (!is_object($user) || $user->coinpayment_transactions()
                    ->where('payment_id', $data['txn_id'])->count()) {
            return;
        }

        $saved = [
            'payment_id' => $data['txn_id'],
            'payment_address' => $data['payment_address'] ?? '',
            'reference' => $data['item_number'] ?? '',
            'coin' => $data['coin'] ?? $data['currency2'],
            'fiat' => config('coinpayment.default_currency'),
            'status_text' => $data['status_text'],
            'status' => $data['status'],
            'payment_created_at' => Carbon::now(),
            'expired' => isset($data['time_expires']) ? date('Y-m-d H:i:s', $data['time_expires']) : Carbon::now()->addHour(),
            'amount' => $data['amountf'] ?? $data['amount2']
        ];

        if ($req) {
            $saved['confirms_needed'] = empty($req->result['confirms_needed']) ? 0 : $req->result['confirms_needed'];
            $saved['qrcode_url'] = empty($req->result['qrcode_url']) ? '' : $req->result['qrcode_url'];
            $saved['status_url'] = empty($req->result['status_url']) ? '' : $req->result['status_url'];
            $saved['payload'] = empty($req->payload) ? json_encode([]) : json_encode($req->payload);
        }

        return $user->coinpayment_transactions()->create($saved);
    }

    public function transactions_list()
    {
        return view('coinpayment.list');
    }

    public function transactions_list_any(Request $req)
    {
        $transaction = auth()->user()->coinpayment_transactions()->orderby('updated_at', 'desc');
        if (!empty($req->coin)) {
            $transaction->where('coin', $req->coin);
        }
        if ($req->status !== 'all') {
            $transaction->where('status', '=', (INT) $req->status);
        }

        return new TransactionResourceCollection($transaction->paginate($req->limit));
    }

    public function manual_check(Request $req)
    {
        $check = $this->api_call('get_tx_info', [
            'txid' => $req->payment_id
        ]);
        if ($check['error'] == 'ok') {
            $data = $check['result'];
            $trx = auth()->user()->coinpayment_transactions()->where('id', $req->id);
            if ($data['status'] > 0 || $data['status'] < 0) {
                $trx->update([
                    'status_text' => $data['status_text'],
                    'status' => $data['status'],
                    'confirmation_at' => ((INT) $data['status'] === 100) ? date('Y-m-d H:i:s', $data['time_completed']) : null
                ]);
                $trx = $trx->first();
                $data['request_type'] = 'schedule_transaction';
                $data['payload'] = (Array) json_decode($trx->payload, true);
                dispatch(new CallbackProcessJob($data));
            }

            return response()->json($trx->first());
        }

        return response()->json([
                    'message' => 'Look like the something wrong!'
                        ], 401);
    }

    public function receive_webhook(Request $req)
    {
        /*
          $txn_id = $_POST['txn_id'];
          $item_name = $_POST['item_name'];
          $item_number = $_POST['item_number'];
          $amount1 = floatval($_POST['amount1']);
          $amount2 = floatval($_POST['amount2']);
          $currency1 = $_POST['currency1'];
          $currency2 = $_POST['currency2'];
          $status = intval($_POST['status']);
          $status_text = $_POST['status_text'];
         */
        $cp_merchant_id = config('coinpayment.coinpayment_merchant_id');
        $cp_ipn_secret = config('coinpayment.coinpayment_ipn_secret');

        /* Filtering */
        if (!empty($req->merchant) && $req->merchant != trim($cp_merchant_id)) {
            logger('No or incorrect Merchant ID passed');

            return response('No or incorrect Merchant ID passed', 401);
        }

        $request = file_get_contents('php://input');
        if ($request === false || empty($request)) {
            logger('Error reading POST data');

            return response('Error reading POST data', 401);
        }

        $hmac = hash_hmac("sha512", $request, trim($cp_ipn_secret));
        if (!hash_equals($hmac, $_SERVER['HTTP_HMAC'])) {
            logger('HMAC signature does not match');

            return response('HMAC signature does not match', 401);
        }

        switch ($req->ipn_type) {
            case 'withdrawal':
                return $this->handleWithdrawalIPN($req);
            default:
                return $this->handleCreateTransactionIPN($req);
        }
    }

    private function handleCreateTransactionIPN(Request $req)
    {
        $log = CoinpaymentTrxLog::where('payment_id', $req->txn_id)->first();
        if (!is_object($log)) {
            $log = $this->create_transaction_record($req->all());
        } else {
            $log->update([
                'status' => $req->status,
                'status_text' => $req->status_text,
            ]);
        }

        dispatch(new IPNHandlerJob([
            'payment_id' => $log->payment_id,
            'payment_address' => $log->payment_address,
            'coin' => $log->coin,
            'fiat' => $log->fiat,
            'status_text' => $log->status_text,
            'status' => $log->status,
            'payment_created_at' => $log->payment_created_at,
            'confirmation_at' => $log->confirmation_at,
            'amount' => $log->amount,
            'confirms_needed' => $log->confirms_needed,
            'payload' => (Array) json_decode($log->payload),
        ]));
    }

    private function handleWithdrawalIPN(Request $req)
    {
        $log = CoinpaymentTrxLog::where('wire_id', $req->id)->first();
        $log->wire_status = $req->status;
        $log->wire_amount = $req->amount;
        $log->wire_currency = $req->currency;
        $log->wire_address = $req->address;
        $log->save();
    }
}
