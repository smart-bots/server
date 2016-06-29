<?php

namespace SmartBots\Http\Composers;

use SmartBots\Hub;

use SmartBots\QuickControl;

class QuickControlComposer {

    public function compose($view) {
        if (session()->has('currentHub')) {

            $quicks = QuickControl::where('user_id',auth()->user()->id)->where('hub_id',session('currentHub'))->get();

            if (!$quicks->isEmpty()) {
                $quicks = $quicks->first()->bots();
            }

            $view->withQuicks($quicks);
        }
    }

}
