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
    public function index() {
        if (auth()->user()->can('viewAllEvents',Hub::findOrFail(session('currentHub')))) {
            $events = Hub::findOrFail(session('currentHub'))->events()->orderBy('id','DESC')->get();
        } else {
            $events = auth()->user()->eventsOf(session('currentHub'))->sortByDesc('id');
        }
        return view('hub.event.index')->withEvents($events);
    }

    public function create() {
        //Lấy tất cả user của hub (member)
        $users = Hub::findOrFail(session('currentHub'))->users()->orderBy('id','DESC')->get();
        $nUsers = [];
        foreach ($users as $user) {
            $nUsers[$user['id']] = $user['username'];
        }
        return view('hub.event.create')->withUsers($nUsers);
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required|max:100',
            'trigger.type' => 'numeric|between:1,3',
            'trigger.bot' => 'exists:bots,id',
            // Kiểm tra xem user's id được add có phải là member của hub không
            'permissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub'),
            'highpermissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub')
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $event = new Event;
        $event->hub_id = session('currentHub');
        $event->name = $request->name;
        if (!empty($event->trigger_type) && !empty($event->trigger_bot)) {
            $event->trigger_type = $request->trigger['type'];
            $event->trigger_bot = $request->trigger['bot'];
        }
        $event->status = 1;
        $event->save();

        $newEventPerm = new EventPermission;
        $newEventPerm->user_id = auth()->user()->id;
        $newEventPerm->event_id = $event->id;
        $newEventPerm->high = true;
        $newEventPerm->save();

        if (is_array($request->permissions)) {
            foreach ($request->permissions as $user_id) {
                $newPerm = new EventPermission;
                $newPerm->event_id = $event->id;
                $newPerm->user_id = $user_id;
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

    public function edit($id) {
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
        return view('hub.event.edit')->withEvent($event)->withUsers($nUsers)->withSelected($sUsers)->withSelected2($sUser2s);
    }

    public function update($id, Request $request) {

        $rules = [
            'name'  => 'required|max:100',
            'trigger.type' => 'required|numeric|between:1,3',
            'trigger.bot' => 'required|numeric|exists:bots,id',
            'permissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub'),
            'highpermissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub')
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $event = Event::findOrFail($id);
        $event->name = $request->name;
        $event->trigger_type = $request->trigger['type'];
        $event->trigger_bot = $request->trigger['bot'];
        $event->save();

        $eventperms = EventPermission::where('event_id',$id)->get();
        $users_of_event_old = array_pluck($eventperms,'user_id');

        if (count($users_of_event_old) > count($request->permissions)) { // Xóa bớt
            $diff = collect($users_of_event_old)->diff($request->permissions);
            EventPermission::whereIn('user_id',$diff->all())->where('event_id',$id)->delete();
        } else { // Hoặc thêm
            $diff = collect($request->permissions)->diff($users_of_event_old)->toArray();
            foreach ($diff as $user_id)
            {
                $newPerm = new EventPermission;
                $newPerm->user_id = $user_id;
                $newPerm->event_id = $id;
                $newPerm->save();
            }
        }

        $eventperms = EventPermission::where('event_id',$id)->where('high',true)->get();
        $users_of_event_old = array_pluck($eventperms,'user_id');

        if (count($users_of_event_old) > count($request->highpermissions)) { // Xóa bớt
            $diff = collect($users_of_event_old)->diff($request->highpermissions);
            EventPermission::whereIn('user_id',$diff->all())->where('event_id',$id)->where('high',true)->update(['high' => false]);
        } else { // Hoặc thêm
            $diff = collect($request->highpermissions)->diff($users_of_event_old)->toArray();
            foreach ($diff as $user_id)
            {
                EventPermission::updateOrCreate(['event_id' => $id, 'user_id' => $user_id],['high' => true]);
            }
        }

        $errors = ['success' => 'Saved successfully'];
        return response()->json($errors);

    }

    public function fire(Request $request)
    {
        $event = Event::findOrFail($request->id);
        if ($event->isActivated()) {
            $event->fire();
            $error = 0;
        } else { $error = 1; }
        return response()->json(['error' => $error]);
    }

    public function deactivate($id)
    {
        Event::findOrFail($id)->deactivate();
        return response()->json(['error' => 0]);
    }

    public function reactivate($id)
    {
        Event::findOrFail($id)->reactivate();
        return response()->json(['error' => 0]);
    }

    public function destroy($id)
    {
        Event::destroy($id);
        return response()->json(['error' => 0]);
    }

    public function search($query,$query2) {
        if (empty($query2)) {
            $events = Event::select(['id','name'])->where('name','LIKE','%'.$query.'%')->orWhere('id','LIKE','%'.$query.'%')->limit(5)->get()->toArray();
        } else {
            $events = Hub::findOrFail($query2)->events()->select(['id','name'])->where('name','LIKE','%'.$query.'%')->orWhere('id','LIKE','%'.$query.'%')->limit(5)->get()->toArray();
        }
        return response()->json($events);
    }
}
