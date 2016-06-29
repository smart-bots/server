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
        $this->composeNotify();
        $this->composeQuickControl();
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

    private function composeNotify() {
        view()->composer('hub.partials.notifications-menu','SmartBots\Http\Composers\NotifyComposer@compose');
    }

    private function composeQuickControl() {
        view()->composer('hub.partials.right-side-bar','SmartBots\Http\Composers\QuickControlComposer@compose');
    }
}
