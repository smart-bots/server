<?php

namespace SmartBots\Http\Composers;

use SmartBots\Hub;

class HubPanelComposer {

	public function compose($view) {
		$hub = Hub::findOrFail(session('currentHub'));
		$view->withHub($hub);
	}

}
