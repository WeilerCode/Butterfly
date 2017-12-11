<?php

namespace Weiler\Butterfly\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuthGuest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect(action('\Weiler\Butterfly\Http\Controllers\Admin\IndexController@index'));
        }

        return $next($request);
    }
}
