<?php

namespace SmartBots\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->composeMenu();
        $this->composeHubPanel();
        $this->composeUserMenu();
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

    private function composeMenu() {
        view()->composer('partials.menu','SmartBots\Http\Composers\MenuComposer@compose');
    }

    private function composeHubPanel() {
        view()->composer('partials.hubPanel','SmartBots\Http\Composers\HubPanelComposer@compose');
    }

    private function composeUserMenu() {
        view()->composer('partials.userMenu','SmartBots\Http\Composers\UserMenuComposer@compose');
    }
}
