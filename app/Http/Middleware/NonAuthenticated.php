<?php

namespace SmartBots\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Session;

class NonAuthenticated
{
    /**
     * Check if user is not authenticated
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
        if (auth()->guard($guard)->check()) {
            return redirect()->to(route('h::index'));
        }

        return $next($request);
    }
}
