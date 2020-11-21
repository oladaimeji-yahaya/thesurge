<?php

namespace App\Library\Coinpayment\Traits;

use App\Library\Coinpayment\Models\CoinpaymentTrxLog;
use function abort;
use function config;
use function json_decode;
use function route;
use function serialize;
use function session;

trait CoinpaymentTrait
{
    public function api_call($cmd, $req = array())
    {
        // Fill these in from your API Keys page
        $public_key = config('coinpayment.public_key');
        $private_key = config('coinpayment.private_key');

        // Set the API command and required fields
        $req['version'] = 1;
        $req['cmd'] = $cmd;
        $req['key'] = $public_key;
        $req['format'] = 'json'; //supported values are json and xml
        // Generate the query string
        $post_data = http_build_query($req, '', '&');

        // Calculate the HMAC signature on the POST data
        $hmac = hash_hmac('sha512', $post_data, $private_key);

        // Create cURL handle and initialize (if needed)
        static $ch = null;
        if ($ch === null) {
            $ch = curl_init('https://www.coinpayments.net/api.php');
            curl_setopt($ch, CURLOPT_FAILONERROR, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('HMAC: ' . $hmac));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        // Execute the call and close cURL handle
        $data = curl_exec($ch);
        // Parse and return data if successful.
        if ($data !== false) {
            if (PHP_INT_SIZE < 8 && version_compare(PHP_VERSION, '5.4.0') >= 0) {
                // We are on 32-bit PHP, so use the bigint as string option. If you are using any API calls with Satoshis it is highly NOT recommended to use 32-bit PHP
                $dec = json_decode($data, true, 512, JSON_BIGINT_AS_STRING);
            } else {
                $dec = json_decode($data, true);
            }
            if ($dec !== null && count($dec)) {
                return $dec;
            } else {
                // If you are using PHP 5.5.0 or higher you can use json_last_error_msg() for a better error message
                return array('error' => 'Unable to parse JSON result (' . json_last_error() . ')');
            }
        } else {
            return array('error' => 'cURL error: ' . curl_error($ch));
        }
    }

    public function url_payload($payload = [])
    {
        $data['note'] = empty($payload['note']) ? '' : $payload['note'];
        $data['amountTotal'] = empty($payload['amountTotal']) ? 1 : $payload['amountTotal'];
        $data['items'] = empty($payload['items']) ? [] : $payload['items'];
        $data['csrf'] = session()->token();
        $data['params'] = empty($payload['params']) ? [] : $payload['params'];
        $data['payload'] = empty($payload['payload']) ? [] : $payload['payload'];

        $params = base64_encode(serialize($data));
        return route('coinpayment.create.transaction', $params);
    }

    public function link_transaction_list()
    {
        return route('coinpayment.transaction.list');
    }

    public function get_payload($serialize)
    {
        $data = unserialize(base64_decode($serialize));
        if (empty($data['csrf']) || $data['csrf'] !== session()->token()) {
            return abort(404);
        }
        unset($data['csrf']);

        return $data;
    }

    public function completeTransaction($txn_id)
    {
        $log = CoinpaymentTrxLog::where('payment_id', $txn_id)->first();
        if (!is_object($log)) {
            return;
        }

        if ($log->status < 0) {
            $paymentFailed = config('coinpayment.post_failure');
            return is_callable($paymentFailed) ? $paymentFailed($log) : null;
        } else if ($log->status == 1) {
            //Make investment as paid through event
            $paymentFailed = config('coinpayment.post_payment');
            return is_callable($paymentFailed) ? $paymentFailed($log) : null;
        } else if ($log->status >= 100) {
            //Make investment as completed through event
            $onVerify = config('coinpayment.post_verify');
            return is_callable($onVerify) ? $onVerify($log) : null;
        }
    }

    public static function getCoinpaymentBtnFormData(array $investment, $form_id = 'coinpayment_btn_form')
    {
        $amountf = $investment['amount'];
//        //Build in app charge
//        $coinpaymentRate = config('coinpayment.rate', 0.005);
//        $coinpaymentAppRate = config('coinpayment.app_rate', 0.05);
//        $totalCharge = $coinpaymentAppRate + $coinpaymentRate;
//        /*
//         * amountf is a number of which when totalCharge percentage is deducted, will give us the charged amount
//         * Expressed as,
//         *
//         * amountf - (totalCharge x amountf) = amount
//         * amountf(1 - totalCharge) = amount
//         * amountf = amount/(1 - totalCharge)
//         */
//        $amountf = $amountf / (1 - $totalCharge);

        $coinpayment['amountf'] = $amountf;
        $coinpayment['currency'] = 'BTC';
        $coinpayment['item_name'] = config('app.name');
        $coinpayment['item_desc'] = $investment['description'];
        $coinpayment['reference'] = $investment['reference'];

        $data = [
            'amountf' => $amountf,
            'btn_form' => self::getButtonForm($coinpayment, $form_id),
            'btn_form_id' => $form_id
        ];
        return $data;
    }

    private static function getButtonForm(array $payment_data, $form_id = 'coinpayment_btn_form')
    {
        $cancel_url = url()->current();
        $ipn_url = url('coinpayment/ipn');
        $merchant = config('coinpayment.coinpayment_merchant_id');
//        $merchant = '606a89bb575311badf510a4a8b79a45e';
        return <<<HTML
        <form action="https://www.coinpayments.net/index.php" id="{$form_id}" method="post" target="_blank" hidden>
                    <input type="hidden" name="cmd" value="_pay_simple">
                    <input type="hidden" name="reset" value="1">
                    <input type="hidden" name="merchant" value="$merchant">
                    <input type="hidden" name="currency" value="{$payment_data['currency']}">
                    <input type="hidden" name="amountf" value="{$payment_data['amountf']}">
                    <input type="hidden" name="item_name" value="{$payment_data['item_name']}">	
                    <input type="hidden" name="item_desc" value="{$payment_data['item_desc']}">	
                    <input type="hidden" name="item_number" value="{$payment_data['reference']}">	
                    <input type="hidden" name="ipn_url" value="$ipn_url">	
                    <input type="hidden" name="cancel_url" value="$cancel_url">	
                </form>
HTML;
    }
}
