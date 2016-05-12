<?php

namespace SmartBots\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use SmartBots\Hub;
use SmartBots\Policies\HubPolicy;

use SmartBots\Bot;
use SmartBots\Policies\BotPolicy;

use SmartBots\Schedule;
use SmartBots\Policies\SchedulePolicy;

// use SmartBots\Automation;
// use SmartBots\Policies\AutomationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'SmartBots\Model' => 'SmartBots\Policies\ModelPolicy',
        Hub::class => HubPolicy::class,
        Bot::class => BotPolicy::class,
        Schedule::class => SchedulePolicy::class,
        // Automation::class => AutomationPolicy::class,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
    }
}
