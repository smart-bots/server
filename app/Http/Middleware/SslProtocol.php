<?php
namespace SmartBots\Http\Middleware;

use Closure;

class SslProtocol {

    public function handle($request, Closure $next)
    {
        $request->setTrustedProxies( [ $request->getClientIp() ] ); // Trust CloudFlare to avoid inf loop

        if (!$request->secure()) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
