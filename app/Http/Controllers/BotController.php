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
	public function index()
    {
        // Lấy tất cả bot của hub
        if (auth()->user()->can('viewControlAllBots',Hub::findOrFail(session('currentHub')))) {
            $bots = Hub::findOrFail(session('currentHub'))->bots()->orderBy('id','DESC')->get();
        } else {
            $bots = auth()->user()->botsOf(session('currentHub'))->sortByDesc('id');
        }
        // Lấy trạng thái của bot (truestatus)
        for ($i=0;$i<count($bots);$i++) {
            if ($bots[$i]['true'] == 0) {
                $bots[$i]['truestatus'] = 2;
            } else {
            	$bots[$i]['truestatus'] = $bots[$i]['status'];
            }
        }
        return view('hub.bot.index')->withBots($bots);
    }

    public function create()
    {
        //Lấy tất cả user của hub (chủ yếu là lấy member) trừ owner của hub ra
        $users = Hub::findOrFail(session('currentHub'))->users()->orderBy('id','DESC')->get();
        $nUsers = [];
        foreach ($users as $user) {
            $nUsers[$user['id']] = $user['username'];
        }
        return view('hub.bot.create')->withUsers($nUsers);
    }

    public function store(Request $request)
    {
        $rules = [
            'name'  => 'required|max:100',
            'description' => 'max:1000',
            'image' => 'image',
            'type'  => 'required|between:1,6',
            'token' => 'required|size:10|unique:bots,token',
            // Kiểm tra xem user's id được add có phải là member của hub không
            'permissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub'),
            'higherpermissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub')
        ];

        $this->validate($request, $rules);

        $bot = new Bot;
        $bot->hub_id      = session('currentHub');
        $bot->name        = $request->name;
        $bot->token       = $request->token;
        $bot->description = $request->description;
        $bot->type        = $request->type;
        $bot->status      = 0;
        $bot->true        = 1;

		if (!empty($request->image_values)) {
			$image_values  = json_decode($request->image_values);
			$image_name    = str_random(10).'.jpg';
			$image_base64  = explode(',', $image_values->data);
			file_put_contents(base_path().env('UPLOAD_IMAGES_FOLDER').'/'.$image_name, base64_decode($image_base64[1]));
			$bot->image = env('UPLOAD_IMAGES_FOLDER').'/'.$image_name;
		}

        $bot->save();
        if (is_array($request->permissions)) {
            foreach ($request->permissions as $user_id) {
                $newPerm = new BotPermission;
                $newPerm->bot_id = $bot->id;
                $newPerm->user_id = $user_id;
                $newPerm->save();
            }
        }
        if (is_array($request->higherpermissions)) {
            foreach ($request->higherpermissions as $user_id) {
                BotPermission::updateOrCreate(['bot_id' => $bot->id, 'user_id' => $user_id],['higher' => true]);
            }
        }

        return redirect()->to(route('h::b::index'));
    }

    public function edit($id)
    {
        $bot = Bot::findOrFail($id);
        $users = Hub::findOrFail(session('currentHub'))->users()->orderBy('id','DESC')->get()->toArray();
        $nUsers = [];
        foreach ($users as $user) {
            $nUsers[$user['id']] = $user['username'];
        }
        $perms = BotPermission::where('bot_id',$id)->get();
        $sUsers = array_pluck($perms,'user_id');
        $perm2s = BotPermission::where('bot_id',$id)->where('higher',true)->get();
        $sUser2s = array_pluck($perm2s,'user_id');
        return view('hub.bot.edit')->withBot($bot)->withUsers($nUsers)->withSelected($sUsers)->withSelected2($sUser2s);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name'  => 'required|max:100',
            'description' => 'max:1000',
            'image' => 'image',
            'permissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub'),
            'higherpermissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub')
        ];

        $this->validate($request, $rules);

        $bot = Bot::findOrFail($id);
        $bot->name        = $request->name;
        $bot->description = $request->description;

		if (!empty($request->image_values)) {
			$image_values  = json_decode($request->image_values);
			$image_name    = str_random(10).'.jpg';
			$image_base64  = explode(',', $image_values->data);
			file_put_contents(base_path().env('UPLOAD_IMAGES_FOLDER').'/'.$image_name, base64_decode($image_base64[1]));
			$bot->image = env('UPLOAD_IMAGES_FOLDER').'/'.$image_name;
		}

        $bot->save();

        $botperms = BotPermission::where('bot_id',$id)->get();
        $users_of_bot_old = array_pluck($botperms,'user_id');

        if (count($users_of_bot_old) > count($request->permissions)) { // Xóa bớt
            $diff = collect($users_of_bot_old)->diff($request->permissions);
            BotPermission::whereIn('user_id',$diff->all())->where('bot_id',$id)->delete();
        } else { // Hoặc thêm
            $diff = collect($request->permissions)->diff($users_of_bot_old)->toArray();
            foreach ($diff as $user_id)
            {
                $newPerm = new BotPermission;
                $newPerm->user_id = $user_id;
                $newPerm->bot_id = $id;
                $newPerm->save();
            }
        }

        $botperms = BotPermission::where('bot_id',$id)->where('higher',true)->get();
        $users_of_bot_old = array_pluck($botperms,'user_id');

        if (count($users_of_bot_old) > count($request->higherpermissions)) { // Xóa bớt
            $diff = collect($users_of_bot_old)->diff($request->higherpermissions);
            BotPermission::whereIn('user_id',$diff->all())->where('bot_id',$id)->where('higher',true)->update(['higher' => false]);
        } else { // Hoặc thêm
            $diff = collect($request->higherpermissions)->diff($users_of_bot_old)->toArray();
            foreach ($diff as $user_id)
            {
                BotPermission::updateOrCreate(['bot_id' => $id, 'user_id' => $user_id],['higher' => true]);
            }
        }

        return redirect()->route('h::b::edit',['id' => $id])->withSuccess(true);
    }

    public function control(Request $request)
    {
        $bot = Bot::findOrFail($request->id);
        if ($bot->isActivated()) {
            $bot->control($request->val);
            $error = 0;
        } else { $error = 1; }
        return response()->json(['error' => $error]);
    }

    public function deactivate($id)
    {
    	Bot::findOrFail($id)->deactivate();
    	return response()->json(['error' => 0]);
    }

    public function reactivate($id)
    {
    	Bot::findOrFail($id)->reactivate();
    	return response()->json(['error' => 0]);
    }

    public function destroy($id)
    {
        Bot::destroy($id);
        return response()->json(['error' => 0]);
    }

    public function search($query,$query2) {
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
