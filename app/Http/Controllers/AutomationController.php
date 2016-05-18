<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

class AutomationController extends Controller
{
    public function index() {
        if (auth()->user()->can('viewAllAutomations',Hub::findOrFail(session('currentHub')))) {
            $automations = Hub::findOrFail(session('currentHub'))->automations()->orderBy('id','DESC')->get();
        } else {
            $automations = auth()->user()->automationsOf(session('currentHub'))->sortByDesc('id');
        }
        return view('hub.automation.index')->withAutomations($automations);
    }

    public function create() {
        return view('hub.automation.create');
    }
}
