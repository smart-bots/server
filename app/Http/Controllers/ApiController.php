<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use SmartBots\Hub;
use SmartBots\Bot;

class ApiController extends Controller
{

    public function up($token,$bot_token,$status,$hard = 0) {

        // $status là trạng thái của bot được hub gửi
        // nếu $hard được gán nghĩa là thay đổi cứng (server phải thay đổi theo)
        // http://dev.env/smartbots/api/BqkbCjdsOIicxLwQo5CT7e9gBKxbcHM9hHJs4JH19UlnsntCmx/up/abcabcabc1/0/1

        if (Hub::where('token',$token)->firstOrFail()->hasBotToken($bot_token))
        {
            $bot = Bot::where('token',$bot_token)->firstOrFail();
            if ($hard == 0) {
                if ($bot->true != 1) {
                    if ($bot->status == $status) {
                        $bot->true = 1;
                        $bot->save();
                        return [true];
                    } else {
                        return [false];
                    }
                } else {
                    return [true];
                }
            } else {
                $bot->true = 1;
                $bot->status = $status;
                $bot->save();
                return [true];
            }
        } else {
            return [false];
        }
    }
    public function down($token) {
        // http://dev.env/smartbots/api/BqkbCjdsOIicxLwQo5CT7e9gBKxbcHM9hHJs4JH19UlnsntCmx/down
        $bots = Hub::where('token',$token)->firstOrFail()->bots()->activated()->where('true',0)->get();
        $nBots = ["c" => count($bots)];
        for ($i=0; $i < count($bots); $i++) {
            $nBots[$i] = $bots[$i]['token'].$bots[$i]['status'];
        }
        return response()->json($nBots);
    }
}
