<?php

namespace SmartBots\Http\Composers;

use SmartBots\User;

class UserMenuComposer {

    public function compose($view) {
        $user = auth()->user();
        $view->withUser($user);
    }

}
