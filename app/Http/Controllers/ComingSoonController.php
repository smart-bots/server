<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

class ComingSoonController extends Controller
{
    public $time = '10 july 2016 12:00:00';

    public function sucscribe(Request $request) {

    }

    public function index() {
        return view('comingsoon.index')->withTime($this->time);
    }
}
