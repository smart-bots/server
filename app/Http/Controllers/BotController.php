<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use Validator;

use SmartBots\User;
use SmartBots\Hub;
use SmartBots\Member;
use SmartBots\Bot;
use SmartBots\Permission;

class BotController extends Controller
{
	public function index()
    {
        // Lấy tất cả bot của hub
        $bots = Hub::findOrFail(session('currentHub'))->bots()->orderBy('id','DESC')->get();
        // Lấy trạng thái của bot
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
        // Lấy tất cả user của hub (chủ yếu là lấy member) trừ owner của hub ra
        $users = Hub::findOrFail(session('currentHub'))->users()->with((['members' => function($query) { $query->where('hub_id',session('currentHub'))->where('level','!=',0); }]))->orderBy('members.id','DESC')->get();
        $nUsers = [];
        foreach ($users as $user) {
            $nUsers[$user['members'][0]['id']] = $user['username'];
        }
        return view('hub.bot.create')->withUsers($nUsers);
    }

    public function store(Request $request)
    {
        $rules = [
            'name'  => 'required|max:100',
            'description' => 'max:1000',
            'image' => 'image',
            'type'  => 'required|in:1,2,3',
            'token' => 'required|size:10|unique:bots,token',
            // Kiểm tra xem member's id được add có phải là member của hub không
            'permissions.*' => 'exists:members,id,hub_id,'.Hub::findOrFail(session('currentHub'))->id
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

        // Thêm quyền điều khiển bot cho member
        if ($request->permissions)
        {
            foreach ($request->permissions as $member_id)
            {
                $newPerm = new Permission;
                $newPerm->bot_id = $bot->id;
                $newPerm->member_id = $member_id;
                $newPerm->save();
            }
        }

        // Thêm quyền điều khiển bot cho owner
        $newPerm = new Permission;
        $newPerm->bot_id = $bot->id;
        $newPerm->member_id = Hub::findOrFail(session('currentHub'))->owner()->getMemberInfoOf(session('currentHub'))->id;
        $newPerm->save();

        return redirect()->to(route('h::b::index'));
    }

    public function edit($id)
    {
        $bot = Bot::findOrFail($id);
        // Lấy member, trừ owner ra
        $users = Hub::findOrFail(session('currentHub'))->users()->with((['members'=>function($query){$query->where('hub_id',session('currentHub'))->where('level','!=',0);}]))->orderBy('members.id','DESC')->get()->toArray();
        $nUsers = [];
        $sUsers = [];
        foreach ($users as $user) {
            $perms = Permission::where('member_id',$user['members'][0]['id'])->get();
            foreach ($perms as $perm)
            {
                if ($perm['bot_id'] == $id) {
                    $sUsers[] = $perm['member_id'];
                }
            }
            $nUsers[$user['members'][0]['id']] = $user['username'];
        }
        return view('hub.bot.edit')->withBot($bot)->withUsers($nUsers)->withSelected($sUsers);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name'  => 'required|max:100',
            'description' => 'max:1000',
            'image' => 'image',
            'permissions.*' => 'exists:members,id,hub_id,'.Hub::findOrFail(session('currentHub'))->id
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

        // Xóa tất cả perm để thêm lại từ đầu (trừ của owner ra)
        Permission::where('bot_id',$id)->where('level','!=',0)->delete();

        if ($request->permissions)
        {
            foreach ($request->permissions as $member_id)
            {
                $newPerm = new Permission;
                $newPerm->bot_id = $bot->id;
                $newPerm->member_id = $member_id;
                $newPerm->save();
            }
        }

        return redirect()->route('h::b::edit',['id' => $id])->withSuccess(true);
    }

    public function control(Request $request)
    {
        $bot = Bot::findOrFail($request->id);
        if ($bot->status != -1) {
            $bot->status = $request->val;
            $bot->true = false;
            $bot->save();
            $error = 0;
        } else { $error = 1; }
        return response()->json(['error' => $error]);
    }

    public function deactivate(Request $request)
    {
    	$hub = Bot::findOrFail($request->id);
    	$hub->status = -1;
    	$hub->save();
    	return response()->json(['error' => 0]);
    }

    public function reactivate(Request $request)
    {
    	$hub = Bot::findOrFail($request->id);
    	$hub->status = 0;
    	$hub->save();
    	return response()->json(['error' => 0]);
    }

    public function destroy(Request $request)
    {
        Bot::destroy($request->id);
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
