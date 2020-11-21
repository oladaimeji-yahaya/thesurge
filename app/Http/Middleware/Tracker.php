<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Tracker
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function isNigerian($request)
    {
        try {
            if (!session('location')) {
                $ip = $_SERVER['REMOTE_ADDR'];
//            $ip = '41.72.160.10'; //Kenya IP
//            $ip = '41.67.178.57'; //Nigeria IP
                $ch = curl_init("https://ipapi.co/$ip/json/");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $country = curl_exec($ch);
                curl_close($ch);
                $request->session()->put('location', $country);
            }

            $location = json_decode(session('location'), true);
            return isset($location['country']) && $location['country'] === 'NG';
        } catch (Exception $e) {
            logger($e);
        }
    }
}
