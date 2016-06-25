<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use Validator;

use SmartBots\Hub;

class HubController extends Controller
{
    /**
     * Listing all the hub that user can access
     * @return Illuminate\Contracts\View\View
     */
    public function index() {
        $hubs = collect([]);

        $members = auth()->user()->members()->activated()->get();
        foreach ($members as $member) {
            $hubs = $hubs->merge($member->hub()->with('bots')->get());
        }

        return view('hub.index')->withHubs($hubs);
    }

    /**
     * Show up hub create form
     * @return Illuminate\Contracts\View\View
     */
    public function create() {
        return view('hub.create');
    }

    /**
     * Handle a request to create new hub
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
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

        $newHub = new Hub;
        $newHub->name        = $request->name;
        $newHub->description = $request->description;
        $newHub->token       = str_random(50);
        $newHub->timezone    = $request->timezone;

        if (!empty($request->image_values)) {
            $newHub->image = upload_base64_image(json_decode($request->image_values)->data);
        }

        $newHub->save();

        $newMember = new Member;
        $newMember->user_id = auth()->user()->id;
        $newMember->hub_id  = $newHub->id;
        $newMember->save();

        $newHubPermission = new HubPermission;
        $newHubPermission->user_id = auth()->user()->id;
        $newHubPermission->hub_id  = $newHub->id;
        $newHubPermission->data    = 0; // Admin
        $newHubPermission->save();

        $errors = ['success' => 'true', 'href' => route('h::index')];
        return response()->json($errors);
    }

    /**
     * Handle a request to login to a hub
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        // Check if user is a member of hub, member is activated
        if (auth()->user()->member($request->id)->isActivated() && auth()->user()->isOf($request->id)) {
            session()->put('currentHub',$request->id);
            return response()->json(['error' => 0]);
        } else abort(403);
    }

    /**
     * Show up dashboard of hub
     * @return Illuminate\Contracts\View\View
     */
    public function dashboard() {
        return view('hub.dashboard');
    }

    /**
     * Show up hub edit form
     * @return Illuminate\Contracts\View\View
     */
    public function edit() {

        $hub = Hub::findOrFail(session('currentHub'));
        return view('hub.edit')->withHub($hub);
    }

    /**
     * Handle a request to edit hub
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function update(Request $request) {

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

    /**
     * Return all changed-status bot
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function botsStatus(Request $request) {

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

    /**
     * Deactivate a hub
     * @return Illuminate\Http\JsonResponse
     */
    public function deactivate() {

        $hub = Hub::findOrFail(session('currentHub'))->deactivate();
        return response()->json(['error' => 0]);
    }

    /**
     * Reactivate a hub
     * @return Illuminate\Http\JsonResponse
     */
    public function reactivate() {

        $hub = Hub::findOrFail(session('currentHub'))->reactivate();
        return response()->json(['error' => 0]);
    }

    /**
     * Logout of a hub
     * @return Illuminate\Http\JsonResponse
     */
    public function logout() {

        session()->forget('currentHub');
        return response()->json(['error' => 0]);
    }

    /**
     * Delete a hub
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy() {

        Hub::destroy(session('currentHub'));
        $this->logout();
        return response()->json(['error' => 0]);
    }
}
