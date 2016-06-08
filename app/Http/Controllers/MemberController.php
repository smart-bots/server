<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use Validator;

use SmartBots\{
    User,
    Hub,
    Member,
    HubPermission,
    BotPermission
};

class MemberController extends Controller
{
    public function index()
    {
        $members = Hub::findOrFail(session('currentHub'))->members()->with('user')->orderBy('id','DESC')->get()->toArray();
        for ($i = 0;$i < count($members);$i++) {
            $members[$i]['bots'] = BotPermission::where('user_id',$members[$i]['user']['id'])->get()->count();
        }
        return view('hub.member.index')->withMembers($members);
    }

    public function create()
    {
        $bots = Hub::findOrFail(session('currentHub'))->bots()->orderBy('id','DESC')->get()->toArray();
        $nBots = [];
        foreach ($bots as $bot) {
            $nBots[$bot['id']] = $bot['name'];
        }
        return view('hub.member.create')->withBots($nBots);
    }

    public function store(Request $request)
    {
        $rules = [
            'username' => 'required|exists:users,username',
            'permissions.*' => 'exists:bots,id,hub_id,'.session('currentHub'),
            'higherpermissions.*' => 'exists:bots,id,hub_id,'.session('currentHub'),
            'hubpermissions.*' => 'between:1,14'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::where('username',$request->username)->firstOrFail();

        // Kiểm tra xem user có phải là member của hub
        if (Hub::findOrFail(session('currentHub'))->hasUser($user->id)) {
            return redirect()->back()->withErrors(['username' => 'This user already a member'])->withInput();
        }

        $newMember = new Member;
        $newMember->user_id = $user->id;
        $newMember->hub_id = session('currentHub');
        $newMember->save();
        if (is_array($request->permissions)) {
            foreach ($request->permissions as $bot_id)
            {
                $newPerm = new BotPermission;
                $newPerm->user_id = $user->id;
                $newPerm->bot_id = $bot_id;
                $newPerm->save();
            }
        }

        if (is_array($request->higherpermissions)) {
            foreach ($request->higherpermissions as $user_id)
            {
                BotPermission::updateOrCreate(['bot_id' => $bot_id, 'user_id' => $user->id],['higher' => true]);
            }
        }
        if (is_array($request->hubpermissions)) {
            foreach ($request->hubpermissions as $data) {
                $newperm = new HubPermission;
                $newperm->user_id = $user->id;
                $newperm->hub_id = session('currentHub');
                $newperm->data = $data;
                $newperm->save();
            }
        }

        $errors = ['success' => 'true', 'href' => route('h::m::index')];
        return response()->json($errors);
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        $user = $member->user;
        // Lấy tất cả bot
        $bots = Hub::findOrFail(session('currentHub'))->bots()->orderBy('id','DESC')->get()->toArray();
        $nBots = [];
        foreach ($bots as $bot) {
            $nBots[$bot['id']] = $bot['name'];
        }
        $perms = BotPermission::where('user_id',$user->id)->whereIn('bot_id',array_pluck($bots,'id'))->get();
        $sBots = array_pluck($perms,'bot_id');

        $perm2s = BotPermission::where('user_id',$user->id)->whereIn('bot_id',array_pluck($bots,'id'))->where('higher',true)->get();
        $sBot2s = array_pluck($perm2s,'bot_id');

        $hubperms = HubPermission::where('user_id',$user->id)->where('hub_id',session('currentHub'))->get();
        $hubperms = array_pluck($hubperms,'data');
        return view('hub.member.edit')->withMem($member)->withBots($nBots)->withSelected($sBots)->withSelected2($sBot2s)->withUsername($user->username)->withHubperms($hubperms);
    }

    public function update(Request $request, $id)
    {
        $rules = ['permissions.*' => 'exists:bots,id,hub_id,'.Hub::findOrFail(session('currentHub'))->id];

		$validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $member = Member::findOrFail($id);

        $user = $member->user;

        $bots = Hub::findOrFail(session('currentHub'))->bots()->orderBy('id','DESC')->get();

        $botperms = BotPermission::where('user_id',$member->user_id)->whereIn('bot_id',array_pluck($bots,'id'))->get();

        $bots_of_user_old = array_pluck($botperms,'bot_id');

        if (count($bots_of_user_old) > count($request->permissions)) { // Xóa bớt
            $diff = collect($bots_of_user_old)->diff($request->permissions);
            BotPermission::where('user_id',$member->user_id)->whereIn('bot_id',$diff->all())->delete();
        } else { // Hoặc thêm
            $diff = collect($request->permissions)->diff($bots_of_user_old)->toArray();
            foreach ($diff as $bot_id)
            {
                $newPerm = new BotPermission;
                $newPerm->user_id = $user->id;
                $newPerm->bot_id = $bot_id;
                $newPerm->save();
            }
        }

        $botperms = BotPermission::where('user_id',$member->user_id)->whereIn('bot_id',array_pluck($bots,'id'))->where('higher',true)->get();

        $bots_of_user_old = array_pluck($botperms,'bot_id');

        if (count($bots_of_user_old) > count($request->higherpermissions)) { // Xóa bớt
            $diff = collect($bots_of_user_old)->diff($request->higherpermissions);
            BotPermission::where('user_id',$member->user_id)->whereIn('bot_id',$diff->all())->where('higher',true)->update(['higher' => false]);
        } else { // Hoặc thêm
            $diff = collect($request->higherpermissions)->diff($bots_of_user_old)->toArray();
            foreach ($diff as $bot_id)
            {
                BotPermission::updateOrCreate(['bot_id' => $bot_id, 'user_id' => $user->id],['higher' => true]);
            }
        }

        $hubperms = HubPermission::where('user_id',$member->user_id)->where('hub_id',session('currentHub'))->get();

        $hubperms_old = array_pluck($hubperms,'data');

        if (count($hubperms_old) > count($request->hubpermissions)) {
            $diff = collect($hubperms_old)->diff($request->hubpermissions);
            HubPermission::where('user_id',$member->user_id)->where('hub_id',session('currentHub'))->whereIn('data',$diff->all())->delete();
        } else {
            $diff = collect($request->hubpermissions)->diff($hubperms_old)->toArray();
            foreach ($diff as $data) {
                HubPermission::firstOrCreate(['user_id' => $user->id, 'hub_id' => session('currentHub'), 'data' => $data]);
            }
        }

        $errors = ['success' => 'Saved successfully'];
        return response()->json($errors);
    }

    public function deactivate($id)
    {
    	$mem = Member::findOrFail($id)->deactivate();
    	return response()->json(['error' => 0]);
    }

    public function reactivate($id)
    {
    	$mem = Member::findOrFail($id)->reactivate();
    	return response()->json(['error' => 0]);
    }

    public function destroy($id)
    {
        Member::destroy($id);
        return response()->json(['error' => 0]);
    }
}
