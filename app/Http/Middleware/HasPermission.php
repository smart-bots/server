<?php

namespace SmartBots\Http\Middleware;

use Closure;

use SmartBots\{
    User,
    Hub,
    Bot,
    Member,
    Schedule,
    Automation,
    Event
};

class HasPermission
{
    /**
     * Check if user has a right permission to access route
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string    $thing
     * @return mixed
     */
    public function handle($request, Closure $next, $thing)
    {
        // https://laravel.com/api/5.1/Illuminate/Http/Request.html

        $routePrefix = $request->route()->getPrefix();

        $routeName = $request->route()->getName();

        // dd($routePrefix,$routeName);

        switch ($routeName) {
            case 'h::b::control':
                $model = Bot::findOrFail($request->id);
                break;
            case 'h::e::fire':
                $model = Event::findOrFail($request->id);
                break;
            case 'h::b::create':
                $model = Hub::findOrFail(session('currentHub'));
                break;
            case 'h::s::create':
                $model = Hub::findOrFail(session('currentHub'));
                break;
            case 'h::a::create':
                $model = Hub::findOrFail(session('currentHub'));
                break;
            case 'h::e::create':
                $model = Hub::findOrFail(session('currentHub'));
                break;
            default:
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
                    case 'hub/event':
                        $model = Event::findOrFail($request->route('id'));
                        break;
                    case '/hub':
                        $model = Hub::findOrFail(session('currentHub'));
                        break;
                }
                break;
        }

        // dd($thing,$model);

        if ($request->user()->cant($thing,$model)) {
            abort(403);
        }

        return $next($request);
    }
}
