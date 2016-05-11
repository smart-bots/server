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

class MemberController extends Controller
{
    public function index()
    {
        // Lấy tất cả member (trừ owner)
        $members = Hub::findOrFail(session('currentHub'))->members()->where('level','!=',0)->with('user')->orderBy('id','DESC')->get()->toArray();
        for ($i = 0;$i < count($members);$i++) {
            $members[$i]['bots'] = Permission::where('member_id',$members[$i]['id'])->get()->count();
        }
        return view('hub.member.index')->withMembers($members);
    }

    public function create()
    {
        // Lấy tất cả bot
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
            'permissions.*' => 'exists:bots,id,hub_id,'.Hub::findOrFail(session('currentHub'))->id
        ];

        $this->validate($request, $rules);

        // Lấy thông tin của user từ username
        $user = User::where('username',$request->username)->firstOrFail();

        // Kiểm tra xem user có phải là member của hub
        if (Hub::findOrFail(session('currentHub'))->hasUser($user->id)) {
            return redirect()->back()->withErrors(['username' => 'This user already a member'])->withInput();
        }

        $newMember = new Member;
        $newMember->user_id = $user->id;
        $newMember->hub_id = session('currentHub');
        $newMember->save();
        if ($request->permissions) {
            foreach ($request->permissions as $bot_id)
            {
                $newPerm = new Permission;
                $newPerm->member_id = $newMember->id;
                $newPerm->bot_id = $bot_id;
                $newPerm->save();
            }
        }

        return redirect()->to(route('h::m::index'));
    }

    public function edit($id)
    {
        // Lấy tất cả bot
        $bots = Hub::findOrFail(session('currentHub'))->bots()->orderBy('id','DESC')->get()->toArray();
        $nBots = [];
        foreach ($bots as $bot) {
            $nBots[$bot['id']] = $bot['name'];
        }
        $mem = Member::findOrFail($id);
        $perms = Member::findOrFail($id)->permissions()->get()->toArray();
        $sBots = array_column($perms,'bot_id');
        $user = Member::findOrFail($id)->user()->firstOrFail();
        return view('hub.member.edit')->withMem($mem)->withBots($nBots)->withSelected($sBots)->withUsername($user->username);
    }

    public function update(Request $request, $id)
    {
        $rules = ['permissions.*' => 'exists:bots,id,hub_id,'.Hub::findOrFail(session('currentHub'))->id];

		$this->validate($request, $rules);

        Permission::where('member_id',$id)->delete();

        if ($request->permissions) {
            foreach ($request->permissions as $bot_id)
            {
                $newPerm = new Permission;
                $newPerm->member_id = $id;
                $newPerm->bot_id = $bot_id;
                $newPerm->save();
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
