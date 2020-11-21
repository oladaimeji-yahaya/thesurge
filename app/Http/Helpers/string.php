<?php

use App\Models\BTCExchangeRate;
use App\Models\BTCInfo;
use App\Models\Exchange;
use Carbon\Carbon;
use function GuzzleHttp\json_decode;

/**
 * @param mixed $find
 * @param mixed $replacements
 * @param mixed $subject
 *
 * @return mixed
 */
function str_replace_recursive($find, $replacements, $subject)
{
    $num_replacements = 0;
    $subject = str_replace($find, $replacements, $subject, $num_replacements);
    if ($num_replacements == 0) {
        return $subject;
    } else {
        return str_replace_recursive($find, $replacements, $subject);
    }
}

/**
 * @param $mixed
 *
 * @return bool
 */
function is_json($mixed)
{
    return (is_string($mixed) and json_decode($mixed) and json_last_error() == JSON_ERROR_NONE);
}

function is_name($name, $numbers_allowed = false)
{
    if ($numbers_allowed) {
        return preg_match("/^[a-zA-Z0-9 ]*$/", $name);
    } else {
        return preg_match("/^[a-zA-Z ]*$/", $name);
    }
}

function normalizeUrl($url)
{
    return str_replace('\\', '/', $url);
}

function normalizePath($path)
{
    return str_replace('/', '\\', $path);
}

/**
 * Fancifully converts numbers to a more human friendly format. e.g 1000 = 1K
 * and 1000000 = 1M
 *
 * @param float $number
 *
 * @return string
 */
function fancyCount($number)
{
    $sizes = ['K', 'M', 'B', 'Z'];
    if ($number < 1000) {
        return $number;
    }
    $i = intval(floor(log($number) / log(1000)));

    return round($number / pow(1000, $i), 2) . $sizes[$i - 1];
}

function format_number($number, $dp = 0)
{
    return number_format(floatval($number), $dp, '.', ',');
}

/**
 * Allows count only to max value
 * @param number $number
 * @param number $max
 * @return string
 */
function fancyMaxCount($number, $max = 99)
{
    return $number > 99 ? "$max+" : "$number";
}

function get_sign($type)
{
    switch ($type) {
        case 'dollar':
            return '$';
        case 'naira':
            return '₦';
        case 'bitcoin':
            return '฿';
    }
}

function fetchFromURL($url, $params = [])
{
    $url = $url . '?' . http_build_query($params);
    if (in_array('curl', get_loaded_extensions())) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $usecurl = true;
    } else {
        $response = file_get_contents($url);
        $usecurl = false;
    }

    if ($response) {
        if ($usecurl) {
            curl_close($ch);
        }
        return $response;
    } else {
        if ($usecurl) {
            $message = 'CURL ERROR: ' . curl_error($ch);
            curl_close($ch);
            throw new Exception($message);
        }
    }
}

function getBTCInfo()
{
    try {
        $url = 'https://api.blockchain.info/stats';
        $response = fetchFromURL($url);
        if ($response) {
            return json_decode($response, true);
        }
    } catch (Exception $exc) {
        logger($exc);
    }

    return [];
}

function getBTCRates()
{
    try {
        $url = 'https://blockchain.info/ticker';
        $response = fetchFromURL($url);
        if ($response) {
            return json_decode($response, true);
        }
    } catch (Exception $exc) {
        logger($exc);
    }

    return [];
}

function shouldUpdateCrypto($table = 'all')
{
    switch ($table) {
        case 'btc':
            $rate = BTCExchangeRate::first();
            break;
        case 'btcinfo':
            $rate = BTCInfo::first();
            break;
        default:
            $rate = Exchange::first();
    }
    if (is_object($rate)) {
        return Carbon::now()->addHour(-1)->gte($rate->updated_at);
    }
    return true;
}

function dollar_to_naira($dollar, $dp = 0)
{
    $rate = setting('naira_dollar', 300);
    return format_naira($dollar * $rate, $dp);
}

function dollar_to_bitcoin($dollar, $dp = 8)
{
    try {
        $number = to_bitcoin($dollar);
        return format_bitcoin($number, $dp);
    } catch (Exception $e) {
        return '-';
    }
}

function to_crypto($amount, $currency = 'btc')
{
    if (is_numeric($currency)) {
        $exchange = Exchange::find($currency);
    } else {
        $exchange = Exchange::symbol($currency);
    }
    if (!$exchange) {
        return;
    }

    return $amount / $exchange->price_usd;
}

function to_bitcoin($amount, $currency = 'USD')
{
//    $converted = to_bitcoin_local($amount, $currency);
//    if ($converted === false) {
//        return to_bitcoin_live($amount, $currency);
//    }
//    return $converted;
    return to_crypto($amount, 'btc');
}

function to_bitcoin_local($amount, $currency = 'USD')
{
    $rate = BTCExchangeRate::where('currency', $currency)->first();
    if ($rate && $rate->last) {
        return $amount / $rate->last;
    }
    return false;
}

function to_bitcoin_live($amount, $currency = 'USD')
{
//    try {
//        $url = 'https://blockchain.info/tobtc';
//        $response = fetchFromURL($url, ['currency' => $currency, 'value' => $amount]);
//        if (is_numeric($response)) {
//            return floatval($response);
//        }
//    } catch (Exception $exc) {
//        logger($exc);
//    }

    return false;
}

function to_currency($number, $dp = 2)
{
    return format_dollar($number, $dp);
}

function format_naira($number, $dp = 2)
{
    return '₦' . format_number($number, $dp);
}

function format_dollar($number, $dp = 2)
{
    return '$' . format_number($number, $dp);
}

function format_bitcoin($number, $dp = 8)
{
    return format_number($number, $dp) . ' BTC';
}

function format_crypto($number, $currency = 'btc', $dp = 8)
{
    if (is_numeric($currency)) {
        $exchange = Exchange::find($currency);
    } else {
        $exchange = Exchange::symbol($currency);
    }
    $symbol = $exchange ? $exchange->symbol : '';

    return format_number($number, $dp) . ' ' . $symbol;
}

function percentage($number, $p = 100)
{
    return ($number * $p / 100);
}

function searchWithWeight($haystack, $needle)
{
    if (starts_with($haystack, $needle)) {
        return 3;
    } elseif (stripos($haystack, $needle) !== false) {
        return 2;
    } else {
        $pattern = preg_replace('/\\s+/', '|', $needle);
        return preg_match("/{$pattern}/i", $haystack) ? 1 : 0;
    }
}
