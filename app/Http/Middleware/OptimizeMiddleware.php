<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OptimizeMiddleware
{
    private static $noOptimize;
    
    public static function off()
    {
        self::$noOptimize = true;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        if (self::$noOptimize ||
                $response instanceof BinaryFileResponse ||
                env('APP_ENV')==='testing' ||
                $response->getStatusCode() === 500) {
            return $response;
        } else {
            $buffer = $response->getContent();
            if (stripos($buffer, '<pre>') !== false || stripos($buffer, '<textarea') !== false) {
                $replace = array(
                    '/<!--[^\[](.*?)[^\]]-->/s' => '',
                    "/<\?php/" => '<?php ',
                    "/\r/" => '',
                    "/>\n</" => '><',
                    "/>\s+\n</" => '><',
                    "/>\n\s+</" => '><',
                );
            } else {
                $replace = array(
                    '/<!--[^\[](.*?)[^\]]-->/s' => '',
                    "/<\?php/" => '<?php ',
                    "/\n([\S])/" => '$1',
                    "/\r/" => '',
                    "/\n/" => '',
                    "/\t/" => '',
                    "/ +/" => ' ',
                );
            }
            $buffer = preg_replace(array_keys($replace), array_values($replace), $buffer);
            $response->setContent($buffer);
            ini_set('zlib.output_compression', 'On'); //enable GZip, too!
            return $response;
        }
    }
}
