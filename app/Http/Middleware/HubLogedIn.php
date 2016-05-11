<?php

namespace SmartBots\Http\Middleware;

use Closure;

use SmartBots\Hub;

class HubLogedIn
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
        if (!session()->has('currentHub') || !Hub::where('id',session('currentHub'))->exists()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->to(route('h::index'));
            }
        }
        return $next($request);
    }
}
