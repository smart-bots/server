<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use Validator;

use SmartBots\User;
use SmartBots\Hub;
use SmartBots\Member;
use SmartBots\Bot;
use SmartBots\BotPermission;

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
            'higherpermissions.*' => 'exists:bots,id,hub_id,'.session('currentHub')
        ];

        $this->validate($request, $rules);

        $user = User::where('username',$request->username)->firstOrFail();

        // Kiểm tra xem user có phải là member của hub
        if (Hub::findOrFail(session('currentHub'))->hasUser($user->id)) {
            return redirect()->back()->withErrors(['username' => 'This user already a member'])->withInput();
        }

        $newMember = new Member;
        $newMember->user_id = $user->id;
        $newMember->hub_id = session('currentHub');
        $newMember->save();

        foreach ($request->permissions as $bot_id)
        {
            $newPerm = new BotPermission;
            $newPerm->user_id = $user->id;
            $newPerm->bot_id = $bot_id;
            $newPerm->save();
        }

        foreach ($request->higherpermissions as $user_id)
        {
            BotPermission::updateOrCreate(['bot_id' => $bot_id, 'user_id' => $user->id],['higher' => true]);
        }

        return redirect()->to(route('h::m::index'));
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
        return view('hub.member.edit')->withMem($member)->withBots($nBots)->withSelected($sBots)->withSelected2($sBot2s)->withUsername($user->username);
    }

    public function update(Request $request, $id)
    {
        $rules = ['permissions.*' => 'exists:bots,id,hub_id,'.Hub::findOrFail(session('currentHub'))->id];

		$this->validate($request, $rules);

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

        return redirect()->route('h::m::edit',['id' => $id])->withSuccess(true);
    }

    public function deactivate(Request $request)
    {
    	$mem = Member::findOrFail($request->id);
    	$mem->status = 0;
    	$mem->save();
    	return response()->json(['error' => 0]);
    }

    public function reactivate(Request $request)
    {
    	$mem = Member::findOrFail($request->id);
    	$mem->status = 1;
    	$mem->save();
    	return response()->json(['error' => 0]);
    }

    public function destroy(Request $request)
    {
        Member::destroy($request->id);
        return response()->json(['error' => 0]);
    }
}
