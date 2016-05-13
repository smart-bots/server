<?php

namespace SmartBots\Http\Middleware;

use Closure;

use SmartBots\{
    User,
    Hub,
    Bot,
    Member,
    Schedule,
    Automation
};

class HasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $thing)
    {
        // https://laravel.com/api/5.1/Illuminate/Http/Request.html
        $routePrefix = $request->route()->getPrefix();
        // dd($routePrefix);
        switch ($routePrefix) {
            case 'hub/member':
                $model = Hub::findOrFail(session('currentHub'));
                break;
            case 'hub/bot':
                $model = Bot::findOrFail($request->route('id'));
                break;
            case 'hub/schedule':
                $model = Schedule::findOrFail($request->route('id'));
                break;
            case 'hub/automation':
                $model = Automation::findOrFail($request->route('id'));
                break;
            case '/hub':
                $model = Hub::findOrFail(session('currentHub'));
                break;
        }
        // dd($model);
        if ($request->user()->cant($thing,$model)) {
            abort(403);
        }
        return $next($request);
    }
}
