<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotSuspended
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
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->status === USER_STATUS_SUSPENDED &&
                    $user->suspended_until &&
                    $user->suspended_until->isPast()) {
                $user->suspended_until = null;
                $user->suspended_for = '';
                $user->status = USER_STATUS_ACTIVE;
                $user->save();
            }

            if ($user->status === USER_STATUS_ACTIVE) {
                return redirect()->route('dashboard.index');
            }
        }

        return $next($request);
    }

}
