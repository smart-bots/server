<?php

namespace SmartBots\Http\Middleware;

use Closure;

class HasPermission
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
        // $request->route('id');
        // https://laravel.com/api/5.1/Illuminate/Http/Request.html
        return $next($request);
    }
}
