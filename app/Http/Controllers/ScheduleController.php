<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;
use Validator;

use SmartBots\{
    Hub,
    Schedule,
    SchedulePermission
};

use Carbon;

class ScheduleController extends Controller
{
    public function index() {
        if (auth()->user()->can('viewAllSchedules',Hub::findOrFail(session('currentHub')))) {
            $schedules = Hub::findOrFail(session('currentHub'))->schedules()->orderBy('id','DESC')->get();
        } else {
            $schedules = auth()->user()->schedulesOf(session('currentHub'))->sortByDesc('id');
        }
        return view('hub.schedule.index')->withSchedules($schedules);
    }

    public function create() {
        //Lấy tất cả user của hub (member)
        $users = Hub::findOrFail(session('currentHub'))->users()->orderBy('id','DESC')->get();
        $nUsers = [];
        foreach ($users as $user) {
            $nUsers[$user['id']] = $user['username'];
        }
        return view('hub.schedule.create')->withUsers($nUsers);
    }

    public function store(Request $request) {

        // dd($request->toArray());
        $now = Carbon::now()->setTimezone(Hub::findorfail(session('currentHub'))->timezone);
        $now->second = 0;

        $rules = [
            'name' => 'required|max:100',
            'description' => 'max:1000',
            'action.type.0' => 'required|numeric|between:1,3',
            'action.bot.0' => 'required|exists:bots,id', // Thiếu check permission
            'action.type.*' => 'numeric|between:1,3',
            'action.bot.*' => 'exists:bots,id',
            'type' => 'required|numeric|between:1,2',
            'condition.type' => 'required|numeric|between:0,2',
            'activate_after' => 'date|after:'.$now->timestamp,
            'deactivate_after_times' => 'numeric',
            'deactivate_after_datetime' => 'date|after:'.$now->timestamp,
            // Kiểm tra xem user's id được add có phải là member của hub không
            'permissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub'),
            'highpermissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub')
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) { return response()->json($validator->errors()); }


        if ($request->type == 1) {

            $rules = ['time' => 'required|date|after:'.$now->timestamp];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) { return response()->json($validator->errors()); }

        } else {

            $rules = [
                'frequency.unit.0' => 'required|numeric|between:1,6',
                'frequency.unit.*' => 'numeric|between:1,6',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) { return response()->json($validator->errors()); }

            $i = 0;
            $rules = [];

            foreach ($request->frequency['unit'] as $unit) {

                switch ($unit) {
                    case 1: // minutes
                        $rules['frequency.value.'.$i] = 'required|numeric|between:1,60';
                        break;
                    case 2: // hours
                        $rules['frequency.value.'.$i] = 'required|numeric|between:1,24';
                        $rules['frequency.at.'.$i] = 'required|numeric|between:0,59';
                        break;
                    case 3: // days
                        $rules['frequency.value.'.$i] = 'required|numeric|between:1,31';
                        $rules['frequency.at.'.$i] = ['required','regex:/^([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/'];
                        break;
                    case 4: // weeks
                        $rules['frequency.value.'.$i] = 'required|numeric|between:1,5';
                        $rules['frequency.at.'.$i] = ['required','regex:/^(Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/'];
                        break;
                    case 5: // months
                        $rules['frequency.value.'.$i] = 'required|numeric|between:1,12';
                        $rules['frequency.at.'.$i] = ['required','regex:/^([0-9]{1}|[012]{1}[0-9]{1}|[3]{1}[01]) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/'];
                        break;
                    case 6: // years
                        $rules['frequency.value.'.$i] = 'required|numeric';
                        $rules['frequency.at.'.$i] = ['required','regex:/^(January|February|March|April|May|June|July|August|September|October|November|December) ([0-9]{1}|[012]{1}[0-9]{1}|[3]{1}[01]) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/'];
                        break;
                }

                $i++;
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) { return response()->json($validator->errors()); }

        }

        if ($request->condition['type'] != 0) {

            $rules = [
                'condition.method' => 'required|numeric|between:1,2',
                'condition.bot.0' => 'required|exists:bots,id',
                'condition.state.0' => 'required|numeric|between:0,1',
                'condition.bot.*' => 'required|exists:bots,id',
                'condition.state.*' => 'required|numeric|between:0,1'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) { return response()->json($validator->errors()); }
        }

        /////////////////// Successfully

        $schedule = new Schedule;

        $schedule->hub_id = session('currentHub');

        $schedule->name = $request->name;
        $schedule->description = $request->description;

        $actionData = '';
        for ($i=0;$i<count($request->action['type']);$i++) {
            $actionData .= $request->action['type'][$i].','.$request->action['bot'][$i].'|';
        }
        $schedule->action = trim($actionData,'|');

        $schedule->type = $request->type;

        if ($request->type == 1) {
            $schedule->data = $request->time;
            $schedule->status = 1;
        } else {

            $frequencyData = '';
            $nextRunData = '';

            for ($i=0; $i<count($request->frequency['value']); $i++) {

                $eachFrequency = $request->frequency['unit'][$i].','.$request->frequency['value'][$i].','.$request->frequency['at'][$i];
                trim($eachFrequency,',');
                $frequencyData .= $eachFrequency.'|';

                if (!empty($request->activated_after)) {
                    $activate_after = Carbon::createFromFormat('j-n-Y H:i', $schedule->activate_after, $schedule->hub->timezone);
                } else {
                    $activate_after = Carbon::now();
                    $activate_after->second = 0;
                }

                switch ($request->frequency['unit'][$i]) {
                    case 1:
                        $tmp = $activate_after;
                        $nextRunData .= ($activate_after->timestamp).'|';
                        break;
                    case 2:
                        $tmp = Carbon::create($activate_after->year, $activate_after->month, $activate_after->day, $activate_after->hour, $request->frequency['at'][$i], 0, $schedule->hub->timezone);

                        if ($tmp->timestamp <= $activate_after->timestamp) {
                            $tmp->addHour($request->frequency['value'][$i]);
                        }

                        $nextRunData .= $tmp->timestamp.'|';
                        break;
                    case 3:
                        $xtmp = explode(':',$request->frequency['at'][$i]);
                        $tmp = Carbon::create($activate_after->year, $activate_after->month, $activate_after->day, $xtmp[0], $xtmp[1], 0, $schedule->hub->timezone);

                        if ($tmp->timestamp <= $activate_after->timestamp) {
                            $tmp->addDay($request->frequency['value'][$i]);
                        }

                        $nextRunData .= $tmp->timestamp.'|';
                        break;
                    case 4:
                        $pattern = '/^(Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/';
                        preg_match($pattern, $request->frequency['at'][$i], $xtmp);
                        $tmp = Carbon::create($activate_after->year, $activate_after->month, $activate_after->day, $xtmp[2], $xtmp[3], 0, $schedule->hub->timezone);

                        switch ($xtmp[1]) {
                            case 'Sunday':
                                $tmp->dayOfWeek = 0;
                                break;
                            case 'Monday':
                                $tmp->dayOfWeek = 1;
                                break;
                            case 'Tuesday':
                                $tmp->dayOfWeek = 2;
                                break;
                            case 'Wednesday':
                                $tmp->dayOfWeek = 3;
                                break;
                            case 'Thurday':
                                $tmp->dayOfWeek = 4;
                                break;
                            case 'Friday':
                                $tmp->dayOfWeek = 5;
                                break;
                            case 'Saturday':
                                $tmp->dayOfWeek = 6;
                                break;
                        }

                        if ($tmp->timestamp <= $activate_after->timestamp) {
                            $tmp->addWeek($request->frequency['value'][$i]);
                        }

                        $nextRunData .= $tmp->timestamp.'|';
                        break;
                    case 5:
                        $pattern = '/^([0-9]{1}|[012]{1}[0-9]{1}|[3]{1}[01]) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/';
                        preg_match($pattern, $request->frequency['at'][$i], $xtmp);
                        $tmp = Carbon::create($activate_after->year, $activate_after->month, $xtmp[1], $xtmp[2], $xtmp[3], 0, $schedule->hub->timezone);

                        if ($tmp->timestamp <= $activate_after->timestamp) {
                            $tmp->addMonth($request->frequency['value'][$i]);
                        }

                        $nextRunData .= $tmp->timestamp.'|';
                        break;
                    case 6:
                        $pattern = '/^(January|February|March|April|May|June|July|August|September|October|November|December) ([0-9]{1}|[012]{1}[0-9]{1}|[3]{1}[01]) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/';
                        preg_match($pattern, $request->frequency['at'][$i], $xtmp);
                        $tmp = Carbon::create($activate_after->year, $xtmp[1], $xtmp[2], $xtmp[3], $xtmp[4], 0, $schedule->hub->timezone);

                        if ($tmp->timestamp <= $activate_after->timestamp) {
                            $tmp->addYear($request->frequency['value'][$i]);
                        }

                        $nextRunData .= $tmp->timestamp.'|';
                        break;
                }

            }
            $schedule->data = trim($frequencyData,'|');
            $schedule->next_run_time = trim($nextRunData,'|');

        }

        if ($request->condition['type'] != 0)
        {
            $schedule->condition_type = $request->condition['type'];
            $schedule->condition_method = $request->condition['method'];

            $conditionData = '';
            for ($i=0;$i<count($request->condition['bot']);$i++) {
                $conditionData .= $request->condition['bot'][$i].','.$request->condition['state'][$i].'|';
            }
            $schedule->condition_data = trim($conditionData,'|');
            dd($schedule->condition_data);
        }

        if (!empty($request->activate_after)) {
            $schedule->activate_after = $request->activate_after;
        } else {
            $schedule->activate_after = '';
            $schedule->status = 1;
        }

        $schedule->deactivate_after_times = $request->deactivate_after_times;
        $schedule->deactivate_after_datetime = $request->deactivate_after_datetime;

        $schedule->save();

        $newSchePerm = new SchedulePermission;
        $newSchePerm->user_id = auth()->user()->id;
        $newSchePerm->schedule_id = $schedule->id;
        $newSchePerm->high = true;
        $newSchePerm->save();

        if (is_array($request->permissions)) {
            foreach ($request->permissions as $user_id) {
                $newPerm = new SchedulePermission;
                $newPerm->schedule_id = $schedule->id;
                $newPerm->user_id = $user_id;
                $newPerm->save();
            }
        }

        if (is_array($request->highpermissions)) {
            foreach ($request->highpermissions as $user_id) {
                SchedulePermission::updateOrCreate(['schedule_id' => $schedule->id, 'user_id' => $user_id],['high' => true]);
            }
        }

        return response()->json(['success' => true, 'href' => route('h::s::index')]);

    }

    public function edit($id) {
        $schedule = Schedule::findOrFail($id);
        $users = Hub::findOrFail(session('currentHub'))->users()->orderBy('id','DESC')->get()->toArray();
        $nUsers = [];
        foreach ($users as $user) {
            $nUsers[$user['id']] = $user['username'];
        }
        $perms = SchedulePermission::where('schedule_id',$id)->get();
        $sUsers = array_pluck($perms,'user_id');
        $perm2s = SchedulePermission::where('schedule_id',$id)->where('high',true)->get();
        $sUser2s = array_pluck($perm2s,'user_id');
        return view('hub.schedule.edit')->withSche($schedule)->withUsers($nUsers)->withSelected($sUsers)->withSelected2($sUser2s);
    }

    public function update($id, Request $request) {

        $rules = [
            'permissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub'),
            'highpermissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub')
        ];

        $scheduleperms = SchedulePermission::where('schedule_id',$id)->get();
        $users_of_schedule_old = array_pluck($scheduleperms,'user_id');

        if (count($users_of_schedule_old) > count($request->permissions)) { // Xóa bớt
            $diff = collect($users_of_schedule_old)->diff($request->permissions);
            SchedulePermission::whereIn('user_id',$diff->all())->where('schedule_id',$id)->delete();
        } else { // Hoặc thêm
            $diff = collect($request->permissions)->diff($users_of_schedule_old)->toArray();
            foreach ($diff as $user_id)
            {
                $newPerm = new SchedulePermission;
                $newPerm->user_id = $user_id;
                $newPerm->schedule_id = $id;
                $newPerm->save();
            }
        }

        $scheduleperms = SchedulePermission::where('schedule_id',$id)->where('high',true)->get();
        $users_of_schedule_old = array_pluck($scheduleperms,'user_id');

        if (count($users_of_schedule_old) > count($request->highpermissions)) { // Xóa bớt
            $diff = collect($users_of_schedule_old)->diff($request->highpermissions);
            SchedulePermission::whereIn('user_id',$diff->all())->where('schedule_id',$id)->where('high',true)->update(['high' => false]);
        } else { // Hoặc thêm
            $diff = collect($request->highpermissions)->diff($users_of_schedule_old)->toArray();
            foreach ($diff as $user_id)
            {
                SchedulePermission::updateOrCreate(['schedule_id' => $id, 'user_id' => $user_id],['high' => true]);
            }
        }

        $errors = ['success' => 'Saved successfully'];
        return response()->json($errors);
    }

    public function deactivate($id) {
        $schedule = Schedule::findorfail($id);
        $schedule->deactivate_after_datetime = '';
        $schedule->deactivate_after_times = 0;
        $schedule->deactivate();
        return response()->json(['error' => 0]);
    }

    public function reactivate($id) {

        $schedule = Schedule::findorfail($id);

        $schedule->activate_after = '';

        $nextRunData = '';
        $frequencies = explode('|',$schedule->data);

        foreach ($frequencies as $frequency) {

            $frequency = explode(',',$frequency);
            $now = Carbon::now();
            $now->second = 0;

            switch ($frequency[0]) {
                case 1:
                    $tmp = $now;
                    $nextRunData .= ($now->timestamp).'|';
                    break;
                case 2:
                    $tmp = Carbon::create($now->year, $now->month, $now->day, $now->hour, $frequency[2], 0, $schedule->hub->timezone);

                    if ($tmp->timestamp <= $now->timestamp) {
                        $tmp->addHour($frequency[1]);
                    }

                    $nextRunData .= $tmp->timestamp.'|';
                    break;
                case 3:
                    $xtmp = explode(':',$frequency[2]);
                    $tmp = Carbon::create($now->year, $now->month, $now->day, $xtmp[0], $xtmp[1], 0, $schedule->hub->timezone);

                    if ($tmp->timestamp <= $now->timestamp) {
                        $tmp->addDay($frequency[1]);
                    }

                    $nextRunData .= $tmp->timestamp.'|';
                    break;
                case 4:
                    $pattern = '/^(Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/';
                    preg_match($pattern, $frequency[2], $xtmp);
                    $tmp = Carbon::create($now->year, $now->month, $now->day, $xtmp[2], $xtmp[3], 0, $schedule->hub->timezone);

                    switch ($xtmp[1]) {
                        case 'Sunday':
                            $tmp->dayOfWeek = 0;
                            break;
                        case 'Monday':
                            $tmp->dayOfWeek = 1;
                            break;
                        case 'Tuesday':
                            $tmp->dayOfWeek = 2;
                            break;
                        case 'Wednesday':
                            $tmp->dayOfWeek = 3;
                            break;
                        case 'Thurday':
                            $tmp->dayOfWeek = 4;
                            break;
                        case 'Friday':
                            $tmp->dayOfWeek = 5;
                            break;
                        case 'Saturday':
                            $tmp->dayOfWeek = 6;
                            break;
                    }

                    if ($tmp->timestamp <= $now->timestamp) {
                        $tmp->addWeek($frequency[1]);
                    }

                    $nextRunData .= $tmp->timestamp.'|';
                    break;
                case 5:
                    $pattern = '/^([012]{1}[0-9]{1}|[3]{1}[01]) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/';
                    preg_match($pattern, $frequency[2], $xtmp);
                    $tmp = Carbon::create($now->year, $now->month, $xtmp[1], $xtmp[2], $xtmp[3], 0, $schedule->hub->timezone);

                    if ($tmp->timestamp <= $now->timestamp) {
                        $tmp->addMonth($frequency[1]);
                    }

                    $nextRunData .= $tmp->timestamp.'|';
                    break;
                case 6:
                    $pattern = '/^(January|February|March|April|May|June|July|August|September|October|November|December) ([012]{1}[0-9]{1}|[3]{1}[01]) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/';
                    preg_match($pattern, $frequency[2], $xtmp);
                    $tmp = Carbon::create($now->year, $xtmp[1], $xtmp[2], $xtmp[3], $xtmp[4], 0, $schedule->hub->timezone);

                    if ($tmp->timestamp <= $now->timestamp) {
                        $tmp->addYear($frequency[1]);
                    }

                    $nextRunData .= $tmp->timestamp.'|';
                    break;
            }
        }

        $schedule->next_run_time = trim($nextRunData,'|');

        $schedule->reactivate();

        return response()->json(['error' => 0]);
    }
}
