<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

class LandingController extends Controller
{
    /**
     * Handle a request to subscribe to app's newletter
     * @param  Request $request
     * @return [type]
     */
    public function subscribe(Request $request) {

    }

    /**
     * Handle a request to contact to app's administrator
     * @param  Request $request
     * @return [type]
     */
    public function contact(Request $request) {

    }

    /**
     * The interface of the page
     * @return Illuminate\Contracts\View\View
     */
    public function index() {
        return view('landing.index');
    }
}
