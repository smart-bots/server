<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use Validator;

use SmartBots\{
    Hub,
    Bot,
    BotPermission
};

class BotController extends Controller
{
    /**
     * Listing all bot that user can see
     * @return Illuminate\Contracts\View\View
     */
    public function index() {

        if (auth()->user()->can('viewControlAllBots',Hub::findOrFail(session('currentHub')))) {
            $bots = Hub::findOrFail(session('currentHub'))->bots()->orderBy('id','DESC')->get();
        } else {
            $bots = auth()->user()->botsOf(session('currentHub'))->sortByDesc('id');
        }

        return view('hub.bot.index')->withBots($bots);
    }

    /**
     * Show up bot create form
     * @return Illuminate\Contracts\View\View
     */
    public function create() {

        $users = Hub::findOrFail(session('currentHub'))->users()->orderBy('id','DESC')->get();
        $nUsers = [];
        foreach ($users as $user) {
            $nUsers[$user['id']] = $user['username'];
        }
        return view('hub.bot.create')->withUsers($nUsers);
    }

    /**
     * Handle a request to create a new bot
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        $rules = [
            'name'              => 'required|max:100',
            'description'       => 'max:1000',
            'image'             => 'image',
            'type'              => 'required|between:1,6',
            'token'             => 'required|size:10|unique:bots,token',
            'permissions.*'     => 'exists:members,user_id,hub_id,'.session('currentHub'),
            'highpermissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub')
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $bot = new Bot;
        $bot->hub_id      = session('currentHub');
        $bot->name        = $request->name;
        $bot->token       = $request->token;
        $bot->description = $request->description;
        $bot->type        = $request->type;
        $bot->status      = 0;
        $bot->true        = 1;

        if (!empty($request->image_values)) {
            if (!empty($request->image_values)) {
                $bot->image = upload_base64_image(json_decode($request->image_values)->data);
            }
        }

        $bot->save();

        $newBotPerm = new BotPermission;
        $newBotPerm->user_id = auth()->user()->id;
        $newBotPerm->bot_id  = $bot->id;
        $newBotPerm->high    = true;
        $newBotPerm->notice  = $request->notice;
        $newBotPerm->save();

        if (is_array($request->permissions)) {
            foreach ($request->permissions as $user_id) {
                $newPerm          = new BotPermission;
                $newPerm->bot_id  = $bot->id;
                $newPerm->user_id = $user_id;
                $newPerm->save();
            }
        }
        if (is_array($request->highpermissions)) {
            foreach ($request->highpermissions as $user_id) {
                BotPermission::updateOrCreate(['bot_id' => $bot->id, 'user_id' => $user_id],['high' => true]);
            }
        }

        $errors = ['success' => 'true', 'href' => route('h::b::index')];
        return response()->json($errors);
    }

    /**
     * Show up bot edit form
     * @param  int    $id
     * @return Illuminate\Contracts\View\View
     */
    public function edit(int $id) {

        $bot    = Bot::findOrFail($id);
        $users  = Hub::findOrFail(session('currentHub'))->users()->orderBy('id','DESC')->get()->toArray();

        $nUsers = [];
        foreach ($users as $user) {
            $nUsers[$user['id']] = $user['username'];
        }

        $perms   = BotPermission::where('bot_id',$id)->get();
        $sUsers  = array_pluck($perms,'user_id');

        $perm2s  = BotPermission::where('bot_id',$id)->where('high',true)->get();
        $sUser2s = array_pluck($perm2s,'user_id');

        return view('hub.bot.edit')
            ->withBot($bot)
            ->withUsers($nUsers)
            ->withSelected($sUsers)
            ->withSelected2($sUser2s);
    }

    /**
     * Handle a request to edit a bot
     * @param  Request $request
     * @param  int     $id
     * @return Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id) {

        $rules = [
            'name'              => 'required|max:100',
            'description'       => 'max:1000',
            'image'             => 'image',
            'permissions.*'     => 'exists:members,user_id,hub_id,'.session('currentHub'),
            'highpermissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub')
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $bot = Bot::findOrFail($id);
        $bot->name        = $request->name;
        $bot->description = $request->description;

        if (!empty($request->image_values)) {
            $image_values = json_decode($request->image_values);
            $image_name   = str_random(10).'.jpg';
            $image_base64 = explode(',', $image_values->data);
            file_put_contents(base_path().env('UPLOAD_IMAGES_FOLDER').'/'.$image_name, base64_decode($image_base64[1]));
            $bot->image   = env('UPLOAD_IMAGES_FOLDER').'/'.$image_name;
        }

        $bot->save();

        $botperms = BotPermission::where('bot_id',$id)->get();
        $users_of_bot_old = array_pluck($botperms,'user_id');

        if (count($users_of_bot_old) > count($request->permissions)) {

            $diff = collect($users_of_bot_old)->diff($request->permissions);
            BotPermission::whereIn('user_id',$diff->all())->where('bot_id',$id)->delete();
        } else {

            $diff = collect($request->permissions)->diff($users_of_bot_old)->toArray();
            foreach ($diff as $user_id) {
                $newPerm = new BotPermission;
                $newPerm->user_id = $user_id;
                $newPerm->bot_id  = $id;
                $newPerm->save();
            }
        }

        $botperms         = BotPermission::where('bot_id',$id)->where('high',true)->get();
        $users_of_bot_old = array_pluck($botperms,'user_id');

        if (count($users_of_bot_old) > count($request->highpermissions)) {

            $diff = collect($users_of_bot_old)->diff($request->highpermissions);
            BotPermission::whereIn('user_id',$diff->all())->where('bot_id',$id)->where('high',true)->update(['high' => false]);
        } else {

            $diff = collect($request->highpermissions)->diff($users_of_bot_old)->toArray();
            foreach ($diff as $user_id) {
                BotPermission::updateOrCreate(['bot_id' => $id, 'user_id' => $user_id],['high' => true]);
            }
        }

        BotPermission::updateOrCreate(['bot_id' => $bot->id, 'user_id' => auth()->user()->id],['notice' => $request->notice]);

        $errors = ['success' => 'Saved successfully'];
        return response()->json($errors);
    }

    /**
     * Handle a request to control a bot
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function control(Request $request) {

        $bot = Bot::findOrFail($request->id);

        if ($bot->isActivated()) {
            $bot->control($request->val);
            $error = 0;
        } else {
            $error = 1;
        }

        return response()->json(['error' => $error]);
    }

    /**
     * Deactivate a bot
     * @param  int    $id
     * @return Illuminate\Http\JsonResponse
     */
    public function deactivate(int $id) {

        Bot::findOrFail($id)->deactivate();
        return response()->json(['error' => 0]);
    }

    /**
     * Reactivate a bot
     * @param  int    $id
     * @return Illuminate\Http\JsonResponse
     */
    public function reactivate(int $id) {

        Bot::findOrFail($id)->reactivate();
        return response()->json(['error' => 0]);
    }

    /**
     * Delete a bot
     * @param  int    $id
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(int $id) {

        Bot::destroy($id);
        return response()->json(['error' => 0]);
    }

    /**
     * Search for a bot
     * @param  string $query  A part of the name or id of bot
     * @param  int    $query2 Id of the bot's hub
     * @return Illuminate\Http\JsonResponse
     */
    public function search(string $query, int $query2) {

        if (empty($query2)) {
            $bots = Bot::select(['id','image','name'])->where('name','LIKE','%'.$query.'%')->orWhere('id','LIKE','%'.$query.'%')->limit(5)->get()->toArray();
        } else {
            $bots = Hub::findOrFail($query2)->bots()->select(['id','image','name'])->where('name','LIKE','%'.$query.'%')->orWhere('id','LIKE','%'.$query.'%')->limit(5)->get()->toArray();
        }

        for ($i=0;$i<count($bots);$i++) {
            $bots[$i]['image'] = asset($bots[$i]['image']);
        }

        return response()->json($bots);
    }
}
