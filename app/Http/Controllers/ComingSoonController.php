<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

class ComingSoonController extends Controller
{
    /**
     * The day that application is ready to run
     * @var string
     */
    public $time = '10 july 2016 12:00:00';

    /**
     * Handle a request to subscribe to app's newletter
     * @param  Request $request
     * @return [type]
     */
    public function sucscribe(Request $request) {

    }

    /**
     * The interface of the page
     * @return Illuminate\Contracts\View\View
     */
    public function index() {
        return view('comingsoon.index')->withTime($this->time);
    }
}
