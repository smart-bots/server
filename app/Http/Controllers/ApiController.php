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
     * @example http://dev.env/smartbots/api/xKEiNRmYyzv3gLzCCdR3gVDtCD8svXRCfHaOEnR1EVuZOn9A7U/up/abcabcabc1/1
     */
    // public function up(string $token,string $bot_token,int $status,int $hard = 0,float $data = 0) {

    public function up(Request $request) {

        $token = $request->hubToken;
        $bot_token = $request->botToken;
        $status = $request->status;
        $hard = $request->hard;
        $data = $request->data;

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
                $bot->control($status, true);
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
     * @example http://dev.env/smartbots/api/xKEiNRmYyzv3gLzCCdR3gVDtCD8svXRCfHaOEnR1EVuZOn9A7U/down
     */
    // public function down(string $token) {
    public function down(Request $request) {

        $token = $request->hubToken;

        $bots  = Hub::where('token',$token)->firstOrFail()->bots()->activated()->where('true',0)->get();
        $nBots = ["c" => count($bots)];

        for ($i=0; $i < count($bots); $i++) {
            $nBots[$i] = [
                'token' => $bots[$i]['token'],
                'state' => $bots[$i]['status']
            ];
        }

        return response()->json($nBots);
    }
}
