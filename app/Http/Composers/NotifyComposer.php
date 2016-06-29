<?php

namespace SmartBots\Http\Composers;

class NotifyComposer {

    public function compose($view) {
        if (session()->has('currentHub')) {
            $notis = auth()->user()->unreadNotisIn(session('currentHub'));
            $view->withNotis($notis);
        }
    }

}
