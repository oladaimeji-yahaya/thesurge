<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfSuspended {

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
            $user->last_seen = \Carbon\Carbon::now();
            $user->save();

            if ($user->status === USER_STATUS_SUSPENDED) {
                return redirect()->route('dashboard.suspended');
            }
        }

        return $next($request);
    }

}
