<?php

namespace SmartBots\Http\Composers;

use SmartBots\Hub;

class HubPanelComposer {

	public function compose($view) {
        if (session()->has('currentHub')) {
    		$hub = Hub::findOrFail(session('currentHub'));
    		$view->withHubPanel($hub);
        }
	}

}
