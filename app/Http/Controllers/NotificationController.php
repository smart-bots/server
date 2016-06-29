<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use SmartBots\Notification;

class NotificationController extends Controller
{
    public function index() {
        $notis = auth()->user()->notisIn(session('currentHub'));
        return view('hub.notification.index')->withNotis($notis);
    }

    public function read(Request $request) {
        Notification::findOrFail($request->id)->read();
    }
}
