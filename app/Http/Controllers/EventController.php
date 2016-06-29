<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use Validator;

use SmartBots\{
    Hub,
    Event,
    EventPermission
};

class EventController extends Controller
{
    /**
     * Listing all event that user can see
     * @return Illuminate\Contracts\View\View
     */
    public function index() {

        if (auth()->user()->can('viewAllEvents',Hub::findOrFail(session('currentHub')))) {
            $events = Hub::findOrFail(session('currentHub'))->events()->orderBy('id','DESC')->get();
        } else {
            $events = auth()->user()->eventsOf(session('currentHub'))->sortByDesc('id');
        }
        return view('hub.event.index')->withEvents($events);
    }

    /**
     * Show up the event create form
     * @return Illuminate\Contracts\View\View
     */
    public function create() {

        $users = Hub::findOrFail(session('currentHub'))->users()->orderBy('id','DESC')->get();
        $nUsers = [];
        foreach ($users as $user) {
            $nUsers[$user['id']] = $user['username'];
        }
        return view('hub.event.create')->withUsers($nUsers);
    }

    /**
     * Handle a request to create new event
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        $rules = [
            'name'              => 'required|max:100',
            'trigger.type'      => 'numeric|between:1,3',
            'trigger.bot'       => 'exists:bots,id',
            'permissions.*'     => 'exists:members,user_id,hub_id,'.session('currentHub'),
            'highpermissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub')
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $event = new Event;
        $event->hub_id = session('currentHub');
        $event->name   = $request->name;
        if (!empty($request->trigger['type']) && !empty($request->trigger['bot'])) {
            $event->trigger_type = $request->trigger['type'];
            $event->trigger_bot  = $request->trigger['bot'];
        }
        $event->status = 1;
        $event->save();

        $newEventPerm = new EventPermission;
        $newEventPerm->user_id  = auth()->user()->id;
        $newEventPerm->event_id = $event->id;
        $newEventPerm->high     = true;
        $newEventPerm->notice = $request->notice;
        $newEventPerm->save();

        if (is_array($request->permissions)) {
            foreach ($request->permissions as $user_id) {
                $newPerm = new EventPermission;
                $newPerm->event_id = $event->id;
                $newPerm->user_id  = $user_id;
                $newPerm->save();
            }
        }

        if (is_array($request->highpermissions)) {
            foreach ($request->highpermissions as $user_id) {
                EventPermission::updateOrCreate(['event_id' => $event->id, 'user_id' => $user_id],['high' => true]);
            }
        }

        $errors = ['success' => 'true', 'href' => route('h::e::index')];
        return response()->json($errors);
    }

    /**
     * Show up event edit form
     * @param  int    $id
     * @return Illuminate\Contracts\View\View
     */
    public function edit(int $id) {

        $event = Event::findOrFail($id);
        $users = Hub::findOrFail(session('currentHub'))->users()->orderBy('id','DESC')->get()->toArray();

        $nUsers = [];
        foreach ($users as $user) {
            $nUsers[$user['id']] = $user['username'];
        }

        $perms = EventPermission::where('event_id',$id)->get();
        $sUsers = array_pluck($perms,'user_id');

        $perm2s = EventPermission::where('event_id',$id)->where('high',true)->get();
        $sUser2s = array_pluck($perm2s,'user_id');

        return view('hub.event.edit')
            ->withEvent($event)
            ->withUsers($nUsers)
            ->withSelected($sUsers)
            ->withSelected2($sUser2s);
    }

    /**
     * Handle a request to edit event
     * @param  [type]  $id
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request) {

        $rules = [
            'name'              => 'required|max:100',
            // 'trigger.type'      => 'required|numeric|between:1,3',
            // 'trigger.bot'       => 'required|numeric|exists:bots,id',
            'permissions.*'     => 'exists:members,user_id,hub_id,'.session('currentHub'),
            'highpermissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub')
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $event = Event::findOrFail($id);
        $event->name         = $request->name;
        // $event->trigger_type = $request->trigger['type'];
        // $event->trigger_bot  = $request->trigger['bot'];
        $event->save();

        $eventperms = EventPermission::where('event_id',$id)->get();
        $users_of_event_old = array_pluck($eventperms,'user_id');

        if (count($users_of_event_old) > count($request->permissions)) {

            $diff = collect($users_of_event_old)->diff($request->permissions);
            EventPermission::whereIn('user_id',$diff->all())->where('event_id',$id)->delete();
        } else {

            $diff = collect($request->permissions)->diff($users_of_event_old)->toArray();
            foreach ($diff as $user_id) {
                $newPerm = new EventPermission;
                $newPerm->user_id  = $user_id;
                $newPerm->event_id = $id;
                $newPerm->save();
            }
        }

        $eventperms         = EventPermission::where('event_id',$id)->where('high',true)->get();
        $users_of_event_old = array_pluck($eventperms,'user_id');

        if (count($users_of_event_old) > count($request->highpermissions)) {

            $diff = collect($users_of_event_old)->diff($request->highpermissions);
            EventPermission::whereIn('user_id',$diff->all())->where('event_id',$id)->where('high',true)->update(['high' => false]);
        } else {

            $diff = collect($request->highpermissions)->diff($users_of_event_old)->toArray();
            foreach ($diff as $user_id) {
                EventPermission::updateOrCreate(['event_id' => $id, 'user_id' => $user_id],['high' => true]);
            }
        }

        EventPermission::updateOrCreate(['event_id' => $id, 'user_id' => auth()->user()->id],['notice' => $request->notice]);

        $errors = ['success' => 'Saved successfully'];
        return response()->json($errors);

    }

    /**
     * Handle a request to fire a event
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function fire(Request $request) {

        $event = Event::findOrFail($request->id);

        if ($event->isActivated()) {
            $event->fire();
            $error = 0;
        } else { $error = 1; }

        return response()->json(['error' => $error]);
    }

    /**
     * Deactivate a event
     * @param  int    $id
     * @return Illuminate\Http\JsonResponse
     */
    public function deactivate(int $id) {

        Event::findOrFail($id)->deactivate();
        return response()->json(['error' => 0]);
    }

    /**
     * Reactivate a event
     * @param  int    $id
     * @return Illuminate\Http\JsonResponse
     */
    public function reactivate(int $id) {

        Event::findOrFail($id)->reactivate();
        return response()->json(['error' => 0]);
    }

    /**
     * Delete a event
     * @param  int    $id
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(int $id) {

        Event::destroy($id);
        return response()->json(['error' => 0]);
    }

    /**
     * Search for a event
     * @param  string $query  A part of the id or the name of event
     * @param  int    $query2 The id of event's hub
     * @return Illuminate\Http\JsonResponse
     */
    public function search(string $query, int $query2) {

        if (empty($query2)) {
            $events = Event::select(['id','name'])->where('name','LIKE','%'.$query.'%')->orWhere('id','LIKE','%'.$query.'%')->limit(5)->get()->toArray();
        } else {
            $events = Hub::findOrFail($query2)->events()->select(['id','name'])->where('name','LIKE','%'.$query.'%')->orWhere('id','LIKE','%'.$query.'%')->limit(5)->get()->toArray();
        }

        return response()->json($events);
    }
}
