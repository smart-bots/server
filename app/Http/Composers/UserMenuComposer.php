<?php

namespace SmartBots\Http\Composers;

use SmartBots\User;

class UserMenuComposer {

	public function compose($view) {
		$user = $hosts = User::select(['username','name','avatar'])->findOrFail(auth()->user()->id);
		$view->withUser($user);
	}

}
