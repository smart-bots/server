<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use SmartBots\{QuickControl, Bot};

use Validator;

class QuickControlController extends Controller
{

    public function add(Request $request) {

        $rules = [
            'id' => 'required|numeric|exists:bots,id,hub_id,'.session('currentHub')
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $quicks = QuickControl::where('user_id',auth()->user()->id)->where('hub_id',session('currentHub'))->get();

        if (!$quicks->isEmpty()) {
            $quicks = $quicks->first()->add($request->id);
        } else {
            $quicks = new QuickControl;
            $quicks->user_id = auth()->user()->id;
            $quicks->hub_id = session('currentHub');
            $quicks->data = $request->id;
            $quicks->save();
        }
        $bot = Bot::select(['id','name','image'])->where('id',$request->id)->firstOrFail();
        $bot->image = asset($bot->image);
        return response()->json(['success' => true, 'bot' => $bot]);
    }

    public function remove(Request $request) {

        $quicks = QuickControl::where('user_id',auth()->user()->id)->where('hub_id',session('currentHub'))->get();

        if (!$quicks->isEmpty()) {
            $quicks = $quicks->first()->remove($request->id);
        }

        return response()->json(['success' => true]);
    }
}
