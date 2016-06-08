<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

class AutomationController extends Controller
{
    public function index() {
        return view('hub.automation.index');
    }

    public function create() {
        return view('hub.automation.create');
    }

    public function edit() {
        return view('hub.automation.edit');
    }
}
