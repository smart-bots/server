<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use SmartBots\Hub;
use SmartBots\Bot;

class ApiController extends Controller
{

    /**
     * Use by hub to send a bot status to server
     * @param  string  $token
     * @param  string  $bot_token
     * @param  int  $status status of the bot
     * @param  int $hard if equal to 1 (true), it's a hard change, the server must follow
     * @return Illuminate\Http\JsonResponse
     * @example http://dev.env/smartbots/api/BqkbCjdsOIicxLwQo5CT7e9gBKxbcHM9hHJs4JH19UlnsntCmx/up/abcabcabc1/0/1
     */
    public function up(string $token,string $bot_token,int $status,int $hard = 0) {

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

    /**
     * Use by hub to receive changed-status bots
     * @param  string $token
     * @return Illuminate\Http\JsonResponse
     * @example http://dev.env/smartbots/api/BqkbCjdsOIicxLwQo5CT7e9gBKxbcHM9hHJs4JH19UlnsntCmx/down
     */
    public function down(string $token) {

        $bots  = Hub::where('token',$token)->firstOrFail()->bots()->activated()->where('true',0)->get();
        $nBots = ["c" => count($bots)];

        for ($i=0; $i < count($bots); $i++) {
            $nBots[$i] = $bots[$i]['token'].$bots[$i]['status'];
        }

        return response()->json($nBots);
    }
}
