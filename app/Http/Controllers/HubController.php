<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use SmartBots\User;
use SmartBots\Hub;
use SmartBots\Bot;
use SmartBots\Member;
use SmartBots\Permission;

class HubController extends Controller
{
    public function index() {
        // Lấy tất cả hub mà user tham gia
        $hubs = User::findOrFail(auth()->user()->id)->hubs()->with('bots')->get()->sortByDesc('id');
        return view('hub.index')->withHubs($hubs);
    }

    public function create() {
    	return view('hub.create');
    }

    public function store(Request $request) {
        $rules = [
            'name'  => 'required|max:100',
            'description' => 'max:1000'
        ];

        $this->validate($request, $rules);

		$newHub = new Hub;
		$newHub->name        = $request->name;
		$newHub->description = $request->description;
		$newHub->token         = str_random(50);
		if (!empty($request->image_values)) {
			$image_values  = json_decode($request->image_values);
			$image_name    = str_random(10).'.jpg';
			$image_base64  = explode(',', $image_values->data);
			file_put_contents(base_path().env('UPLOAD_IMAGES_FOLDER').'/'.$image_name, base64_decode($image_base64[1]));
			$newHub->image = env('UPLOAD_IMAGES_FOLDER').'/'.$image_name;
		}
		$newHub->save();

        $newMember = new Member;
        $newMember->user_id = auth()->user()->id;
        $newMember->hub_id = $newHub->id;
        $newMember->level = 0;
        $newMember->save();

        // Thêm add permission

		return redirect()->to(route('h::index'));
    }

    public function login(Request $request)
    {
        $error = 1;
        // Kiểm tra xem user có tham gia hub hay không
        if (User::findOrFail(auth()->user()->id)->isOf($request->id)) {
            session()->put('currentHub',$request->id);
            $error = 0;
        }
        return response()->json(['error' => $error]);
    }

    public function dashboard()
    {
        return redirect()->route('h::edit');
    }

    public function edit()
    {
        $hub = Hub::findOrFail(session('currentHub'));
        return view('hub.edit')->withHub($hub);
    }

    public function update(Request $request)
    {
        $rules = [
            'name'  => 'required|max:100',
            'token'   => 'sometimes|required|size:50|unique:hubs',
            'description' => 'max:1000'
        ];

        $this->validate($request, $rules);

		$hub = Hub::findOrFail(session('currentHub'));
		$hub->name        = $request->name;
		$hub->description = $request->description;
		if (!empty($request->token)) {
			$hub->token   = $request->token;
		}
		if (!empty($request->image_values)) {
			$image_values  = json_decode($request->image_values);
			$image_name    = str_random(10).'.jpg';
			$image_base64  = explode(',', $image_values->data);
			file_put_contents(base_path().env('UPLOAD_IMAGES_FOLDER').'/'.$image_name, base64_decode($image_base64[1]));
			$hub->image = env('UPLOAD_IMAGES_FOLDER').'/'.$image_name;
		}
		$hub->save();

		return view('hub.edit')->withSuccess(true)->withHub($hub);
    }

    public function botsStatus(Request $request)
    {
        // Lấy toàn bộ bot trong hub
        $bots = Hub::findOrFail(session('currentHub'))->bots;
        $bots2 = [];
        for ($i=0;$i<count($bots);$i++) {
            if ($bots[$i]['true'] == 0) {
                $status = 2;
            } else {
            	$status = $bots[$i]['status'];
            }
            $bots2[$bots[$i]['id']] = $status;
        }
        return response()->json($bots2);
    }

    public function deactivate()
    {
    	$hub = Hub::findOrFail(session('currentHub'));
    	$hub->status = 0;
    	$hub->save();
    	return response()->json(['error' => 0]);
    }

    public function reactivate()
    {
    	$hub = Hub::findOrFail(session('currentHub'));
    	$hub->status = 1;
    	$hub->save();
    	return response()->json(['error' => 0]);
    }

    public function logout()
    {
        session()->flush();
        return response()->json(['error' => 0]);
    }

    public function destroy()
    {
        Hub::destroy(session('currentHub'));
        $this->logout();
   		return response()->json(['error' => 0]);
    }
}
