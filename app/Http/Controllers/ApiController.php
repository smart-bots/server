<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use SmartBots\Hub;
use SmartBots\Bot;

class ApiController extends Controller
{

	public function up($key,$bot_key,$status,$hard = 0) {
        // $status là trạng thái của bot được host gửi
        // nếu $hard được gán nghĩa là thay đổi cứng (server phải thay đổi theo)
        // http://localhost/ss/api/1MH9hU6bNMWB0XQoM0jnESyckf30GnfzOA9ZmdIL1OLlU8XoxK/up/abcabcabc1/0/1
        if (Hub::where('key',$key)->firstOrFail()->hasBotKey($bot_key))
        {
            $bot = Bot::where('key',$bot_key)->firstOrFail();
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
    public function down($host_key) {
        // http://localhost/ss/api/1MH9hU6bNMWB0XQoM0jnESyckf30GnfzOA9ZmdIL1OLlU8XoxK/down
        $bots = Hub::where('key',$host_key)->firstOrFail()->bots()->where('true',0)->get();
        $nBots = ["c" => count($bots)];
        for ($i=0; $i < count($bots); $i++) {
            $nBots[$i] = $bots[$i]['key'].$bots[$i]['status'];
        }
        return response()->json($nBots);
    }
}
