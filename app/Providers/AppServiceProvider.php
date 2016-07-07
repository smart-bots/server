<?php

namespace SmartBots\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // if ($this->app->environment('production')) {
        //     $this->app->register(\Jenssegers\Rollbar\RollbarServiceProvider::class);
        // }
        // if (!\App::environment('local')) {
        //     \URL::forceSchema('https');
        // }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
