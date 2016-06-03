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
        view()->composer('hub.partials.menu','SmartBots\Http\Composers\MenuComposer@compose');
    }

    private function composeHubPanel() {
        view()->composer('hub.partials.hub-panel','SmartBots\Http\Composers\HubPanelComposer@compose');
    }

    private function composeUserMenu() {
        view()->composer('hub.partials.user-menu','SmartBots\Http\Composers\UserMenuComposer@compose');
    }
}
