<?php

namespace SmartBots\Http\Middleware;

use Closure;

use Session;

class Language
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

        app()->setLocale(Session::get('language'));

        return $next($request);
    }
}
