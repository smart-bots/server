<?php

namespace SmartBots\Providers;

use Illuminate\Support\ServiceProvider;

use Validator;

use SmartBots\{
    User,
    Hub,
    Member,
    Bot,
    Schedule,
    Event,
    Automation,
    HubPermission,
    BotPermission,
    SchedulePermission,
    EventPermission,
    AutomationPermission
};

use Hash;

class ValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('botCanBeSeen', function($attribute, $value, $parameters, $validator) {
            return auth()->user()->can('low',Bot::findOrFail($value));
        });

        Validator::extend('currentpassword', function($attribute, $value, $parameters, $validator) {
            if (Hash::check($value, auth()->user()->password)) {
                return true;
            } else {
                return false;
            }
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
