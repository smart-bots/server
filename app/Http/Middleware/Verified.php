<?php

namespace SmartBots\Http\Middleware;

use Closure;

class Verified
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
        if (!auth()->user()->verified) {
            if ($request->ajax() || $request->wantsJson()) {
                return abort(401);
            } else {
                return redirect()->route('a::verify');
            }
        }

        return $next($request);
    }
}
