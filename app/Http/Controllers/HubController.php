<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use Validator;

use SmartBots\{
    User,
    Hub,
    Member,
    HubPermission
};

class HubController extends Controller
{
    public function index() {
        $hubs = collect([]);
        // chỉ lấy hub mà member đang active
        $members = auth()->user()->members()->activated()->get();
        foreach ($members as $member) {
            $hubs = $hubs->merge($member->hub()->with('bots')->get());
        }
        return view('hub.index')->withHubs($hubs);
    }

    public function create() {
        return view('hub.create');
    }

    public function store(Request $request) {
        $rules = [
            'name'        => 'required|max:100',
            'description' => 'max:1000',
            'timezone'    => 'required|timezone'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $newHub              = new Hub;
        $newHub->name        = $request->name;
        $newHub->description = $request->description;
        $newHub->token       = str_random(50);
        $newHub->timezone    = $request->timezone;

        if (!empty($request->image_values)) {
            $newHub->image = upload_base64_image(json_decode($request->image_values)->data);
        }

        $newHub->save();

        $newMember          = new Member;
        $newMember->user_id = auth()->user()->id;
        $newMember->hub_id  = $newHub->id;
        $newMember->save();

        $newHubPermission          = new HubPermission;
        $newHubPermission->user_id = auth()->user()->id;
        $newHubPermission->hub_id  = $newHub->id;
        $newHubPermission->data    = 0; // root admin
        $newHubPermission->save();

        $errors = ['success' => 'true', 'href' => route('h::index')];
        return response()->json($errors);
    }

    public function login(Request $request)
    {
        // Kiểm tra: có phải member, member có active?
        if (auth()->user()->member($request->id)->isActivated() && auth()->user()->isOf($request->id)) {
            session()->put('currentHub',$request->id);
            return response()->json(['error' => 0]);
        } else abort(403);
    }

    public function dashboard()
    {
        // if (auth()->user()->can('view',Hub::findOrFail(session('currentHub')))) {
        //     return redirect()->route('h::edit');
        // } else { return redirect()->route('h::b::index'); }

        return view('hub.dashboard');
    }

    public function edit()
    {
        $hub = Hub::findOrFail(session('currentHub'));
        return view('hub.edit')->withHub($hub);
    }

    public function update(Request $request)
    {
        $rules = [
            'name'        => 'required|max:100',
            'token'       => 'required|size:50|unique:hubs,id,'.session('currentHub'),
            'description' => 'max:1000',
            'timezone'    => 'required|timezone'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $hub              = Hub::findOrFail(session('currentHub'));
        $hub->name        = $request->name;
        $hub->description = $request->description;
        $hub->token       = $request->token;
        $hub->timezone    = $request->timezone;

        if (!empty($request->image_values)) {
            $hub->image = upload_base64_image(json_decode($request->image_values)->data);
        }

        $hub->save();

        return response()->json(['success' => 'Saved successfully']);
    }

    // Get bot status

    public function botsStatus(Request $request)
    {
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
        $hub = Hub::findOrFail(session('currentHub'))->deactivate();
        return response()->json(['error' => 0]);
    }

    public function reactivate()
    {
        $hub = Hub::findOrFail(session('currentHub'))->reactivate();
        return response()->json(['error' => 0]);
    }

    public function logout()
    {
        session()->forget('currentHub');
        return response()->json(['error' => 0]);
    }

    public function destroy()
    {
        Hub::destroy(session('currentHub'));
        $this->logout();
        return response()->json(['error' => 0]);
    }
}
