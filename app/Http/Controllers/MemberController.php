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
    SchedulePermission,
    AutomationPermission,
    EventPermission,
    BotPermission
};

class MemberController extends Controller
{
    /**
     * Listing all member that user can see
     * @return Illuminate\Contracts\View\View
     */
    public function index() {

        $members = Hub::findOrFail(session('currentHub'))->members()->with('user')->orderBy('id','DESC')->get()->toArray();

        for ($i = 0;$i < count($members);$i++) {
            $members[$i]['bots'] = BotPermission::where('user_id',$members[$i]['user']['id'])->get()->count();
        }

        return view('hub.member.index')->withMembers($members);
    }

    /**
     * Show up member create (add new member) form
     * @return Illuminate\Contracts\View\View
     */
    public function create() {

        $bots = Hub::findOrFail(session('currentHub'))->bots()->orderBy('id','DESC')->get()->toArray();
        $nBots = [];
        foreach ($bots as $bot) {
            $nBots[$bot['id']] = $bot['name'];
        }

        // ------------------------------------------------------------------------------------------------------------

        $sches = Hub::findOrFail(session('currentHub'))->schedules()->orderBy('id','DESC')->get()->toArray();
        $nSches = [];
        foreach ($sches as $sche) {
            $nSches[$bot['id']] = $sche['name'];
        }

        // ------------------------------------------------------------------------------------------------------------

        $autos = Hub::findOrFail(session('currentHub'))->automations()->orderBy('id','DESC')->get()->toArray();
        $nAutos = [];
        foreach ($autos as $auto) {
            $nAutos[$bot['id']] = $auto['name'];
        }

        // ------------------------------------------------------------------------------------------------------------

        $events = Hub::findOrFail(session('currentHub'))->events()->orderBy('id','DESC')->get()->toArray();
        $nEvents = [];
        foreach ($events as $event) {
            $nEvents[$bot['id']] = $event['name'];
        }

        return view('hub.member.create')
            ->withBots($nBots)
            ->withSchedules($nSches)
            ->withAutomations($nAutos)
            ->withEvents($nEvents);
    }

    /**
     * Handle a request to add new member
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        $rules = [
            'username'                      => 'required|exists:users,username',
            'permissions.bot.low.*'         => 'exists:bots,id,hub_id,'.session('currentHub'),
            'permissions.bot.high.*'        => 'exists:bots,id,hub_id,'.session('currentHub'),
            'permissions.schedule.low.*'    => 'exists:schedules,id,hub_id,'.session('currentHub'),
            'permissions.schedule.high.*'   => 'exists:schedules,id,hub_id,'.session('currentHub'),
            'permissions.event.low.*'       => 'exists:events,id,hub_id,'.session('currentHub'),
            'permissions.event.high.*'      => 'exists:events,id,hub_id,'.session('currentHub'),
            'permissions.automation.low.*'  => 'exists:automations,id,hub_id,'.session('currentHub'),
            'permissions.automation.high.*' => 'exists:automations,id,hub_id,'.session('currentHub'),
            'hubpermissions.*'              => 'numeric|between:1,17'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::where('username',$request->username)->firstOrFail();

        if (Hub::findOrFail(session('currentHub'))->hasUser($user->id)) {
            $errors = ['username' => 'This user already a member'];
            return response()->json($errors);
        }

        $newMember = new Member;
        $newMember->user_id = $user->id;
        $newMember->hub_id  = session('currentHub');
        $newMember->save();

        if (isset($request->permissions['bot']['low'])) {
            foreach ($request->permissions['bot']['low'] as $bot_id) {

                $newPerm = new BotPermission;
                $newPerm->user_id = $user->id;
                $newPerm->bot_id  = $bot_id;
                $newPerm->save();
            }
        }

        if (isset($request->permissions['bot']['high'])) {
            foreach ($request->permissions['bot']['high'] as $bot_id) {
                BotPermission::updateOrCreate(['bot_id' => $bot_id, 'user_id' => $user->id],['high' => true]);
            }
        }

        // ------------------------------------------------------------------------------------------------------------

        if (isset($request->permissions['schedule']['low'])) {
            foreach ($request->permissions['schedule']['low'] as $schedule_id) {

                $newPerm = new SchedulePermission;
                $newPerm->user_id     = $user->id;
                $newPerm->schedule_id = $schedule_id;
                $newPerm->save();
            }
        }

        if (isset($request->permissions['schedule']['high'])) {
            foreach ($request->permissions['schedule']['high'] as $schedule_id) {
                SchedulePermission::updateOrCreate(['schedule_id' => $schedule_id, 'user_id' => $user->id],['high' => true]);
            }
        }

        // ------------------------------------------------------------------------------------------------------------

        if (isset($request->permissions['event']['low'])) {
            foreach ($request->permissions['event']['low'] as $event_id) {

                $newPerm = new EventPermission;
                $newPerm->user_id  = $user->id;
                $newPerm->event_id = $event_id;
                $newPerm->save();
            }
        }

        if (isset($request->permissions['event']['high'])) {
            foreach ($request->permissions['event']['high'] as $event_id) {
                EventPermission::updateOrCreate(['event_id' => $event_id, 'user_id' => $user->id],['high' => true]);
            }
        }

        // ------------------------------------------------------------------------------------------------------------

        if (isset($request->permissions['automation']['low'])) {
            foreach ($request->permissions['automation']['low'] as $automation_id) {

                $newPerm = new AutomationPermission;
                $newPerm->user_id       = $user->id;
                $newPerm->automation_id = $automation_id;
                $newPerm->save();
            }
        }

        if (isset($request->permissions['automation']['high'])) {
            foreach ($request->permissions['automation']['high'] as $automation_id) {
                AutomationPermission::updateOrCreate(['automation_id' => $automation_id, 'user_id' => $user->id],['high' => true]);
            }
        }

        // ------------------------------------------------------------------------------------------------------------

        if (isset($request->hubpermissions)) {
            foreach ($request->hubpermissions as $data) {

                $newperm = new HubPermission;
                $newperm->user_id = $user->id;
                $newperm->hub_id  = session('currentHub');
                $newperm->data    = $data;
                $newperm->save();
            }
        }

        $errors = ['success' => 'true', 'href' => route('h::m::index')];
        return response()->json($errors);
    }

    /**
     * Show up member edit form
     * @param  int    $id
     * @return Illuminate\Contracts\View\View
     */
    public function edit(int $id)
    {
        $member = Member::findOrFail($id);
        $user = $member->user;

        $bots = Hub::findOrFail(session('currentHub'))->bots()->orderBy('id','DESC')->get()->toArray();
        $nBots = [];
        foreach ($bots as $bot) {
            $nBots[$bot['id']] = $bot['name'];
        }

        // ------------------------------------------------------------------------------------------------------------

        $sches = Hub::findOrFail(session('currentHub'))->schedules()->orderBy('id','DESC')->get()->toArray();
        $nSches = [];
        foreach ($sches as $sche) {
            $nSches[$bot['id']] = $sche['name'];
        }

        // ------------------------------------------------------------------------------------------------------------

        $autos = Hub::findOrFail(session('currentHub'))->automations()->orderBy('id','DESC')->get()->toArray();
        $nAutos = [];
        foreach ($autos as $auto) {
            $nAutos[$bot['id']] = $auto['name'];
        }

        // ------------------------------------------------------------------------------------------------------------

        $events = Hub::findOrFail(session('currentHub'))->events()->orderBy('id','DESC')->get()->toArray();
        $nEvents = [];
        foreach ($events as $event) {
            $nEvents[$bot['id']] = $event['name'];
        }

        // ------------------------------------------------------------------------------------------------------------

        $perms = BotPermission::where('user_id',$user->id)->whereIn('bot_id',array_pluck($bots,'id'))->get();
        $sBots = array_pluck($perms,'bot_id');

        $perm2s = BotPermission::where('user_id',$user->id)->whereIn('bot_id',array_pluck($bots,'id'))->where('high',true)->get();
        $sBot2s = array_pluck($perm2s,'bot_id');

        // ------------------------------------------------------------------------------------------------------------

        $perms      = SchedulePermission::where('user_id',$user->id)->whereIn('schedule_id',array_pluck($sches,'id'))->get();
        $sSchedules = array_pluck($perms,'schedule_id');

        $perm2s      = SchedulePermission::where('user_id',$user->id)->whereIn('schedule_id',array_pluck($sches,'id'))->where('high',true)->get();
        $sSchedule2s = array_pluck($perm2s,'schedule_id');

        // ------------------------------------------------------------------------------------------------------------

        $perms   = EventPermission::where('user_id',$user->id)->whereIn('event_id',array_pluck($events,'id'))->get();
        $sEvents = array_pluck($perms,'event_id');

        $perm2s   = EventPermission::where('user_id',$user->id)->whereIn('event_id',array_pluck($events,'id'))->where('high',true)->get();
        $sEvent2s = array_pluck($perm2s,'event_id');

        // ------------------------------------------------------------------------------------------------------------

        $perms        = AutomationPermission::where('user_id',$user->id)->whereIn('automation_id',array_pluck($autos,'id'))->get();
        $sAutomations = array_pluck($perms,'automation_id');

        $perm2s        = AutomationPermission::where('user_id',$user->id)->whereIn('automation_id',array_pluck($autos,'id'))->where('high',true)->get();
        $sAutomation2s = array_pluck($perm2s,'automation_id');

        // ------------------------------------------------------------------------------------------------------------

        $hubperms = HubPermission::where('user_id',$user->id)->where('hub_id',session('currentHub'))->get();
        $hubperms = array_pluck($hubperms,'data');

        return view('hub.member.edit')
            ->withMem($member)
            ->withBots($nBots)
            ->withSchedules($nSches)
            ->withAutomations($nAutos)
            ->withEvents($nEvents)
            ->withSltdBotLow($sBots)
            ->withSltdBotHigh($sBot2s)
            ->withSltdScheduleLow($sSchedules)
            ->withSltdScheduleHigh($sSchedule2s)
            ->withSltdEventLow($sEvents)
            ->withSltdEventHigh($sEvent2s)
            ->withSltdAutomationLow($sAutomations)
            ->withSltdAutomationHigh($sAutomation2s)
            ->withUsername($user->username)
            ->withHubperms($hubperms);
    }

    /**
     * Handle a request to edit a member
     * @param  Request $request
     * @param  int     $id
     * @return Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id) {

        $rules = [
            'permissions.bot.low.*'         => 'exists:bots,id,hub_id,'.session('currentHub'),
            'permissions.bot.high.*'        => 'exists:bots,id,hub_id,'.session('currentHub'),
            'permissions.schedule.low.*'    => 'exists:schedules,id,hub_id,'.session('currentHub'),
            'permissions.schedule.high.*'   => 'exists:schedules,id,hub_id,'.session('currentHub'),
            'permissions.event.low.*'       => 'exists:events,id,hub_id,'.session('currentHub'),
            'permissions.event.high.*'      => 'exists:events,id,hub_id,'.session('currentHub'),
            'permissions.automation.low.*'  => 'exists:automations,id,hub_id,'.session('currentHub'),
            'permissions.automation.high.*' => 'exists:automations,id,hub_id,'.session('currentHub'),
            'hubpermissions.*'              => 'numeric|between:1,17'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $member = Member::findOrFail($id);

        $user = $member->user;

        // ------------------------------------------------------------------------------------------------------------

        $bot_low         = [];
        $bot_high        = [];
        $schedule_low    = [];
        $schedule_high   = [];
        $event_low       = [];
        $event_high      = [];
        $automation_low  = [];
        $automation_high = [];

        // ------------------------------------------------------------------------------------------------------------

        if (isset($request->permissions['bot']['low'])) {
            $bot_low = $request->permissions['bot']['low'];
        }

        if (isset($request->permissions['bot']['high'])) {
            $bot_high = $request->permissions['bot']['high'];
        }

        if (isset($request->permissions['schedule']['low'])) {
            $schedule_low = $request->permissions['schedule']['low'];
        }

        if (isset($request->permissions['schedule']['high'])) {
            $schedule_high = $request->permissions['schedule']['high'];
        }

        if (isset($request->permissions['event']['low'])) {
            $event_low = $request->permissions['event']['low'];
        }

        if (isset($request->permissions['event']['high'])) {
            $event_high = $request->permissions['event']['high'];
        }

        if (isset($request->permissions['automation']['low'])) {
            $automation_low = $request->permissions['automation']['low'];
        }

        if (isset($request->permissions['automation']['high'])) {
            $automation_high = $request->permissions['automation']['high'];
        }

        // ------------------------------------------------------------------------------------------------------------

        $bots     = Hub::findOrFail(session('currentHub'))->bots()->orderBy('id','DESC')->get();

        $botperms = BotPermission::where('user_id',$member->user_id)->whereIn('bot_id',array_pluck($bots,'id'))->get();

        $bots_of_user_old = array_pluck($botperms,'bot_id');

        if (!isset($request->permissions['bot']['low'])) {
            $request->permissions['bot']['low'] = [];
        }

        if (count($bots_of_user_old) > count($bot_low)) {
            $diff = collect($bots_of_user_old)->diff($bot_low);
            BotPermission::where('user_id',$member->user_id)->whereIn('bot_id',$diff->all())->delete();
        } else {
            $diff = collect($bot_low)->diff($bots_of_user_old)->toArray();
            foreach ($diff as $bot_id) {
                $newPerm = new BotPermission;
                $newPerm->user_id = $user->id;
                $newPerm->bot_id  = $bot_id;
                $newPerm->save();
            }
        }

        $botperms = BotPermission::where('user_id',$member->user_id)->whereIn('bot_id',array_pluck($bots,'id'))->where('high',true)->get();

        $bots_of_user_old = array_pluck($botperms,'bot_id');

        if (count($bots_of_user_old) > count($bot_high)) {

            $diff = collect($bots_of_user_old)->diff($bot_high);
            BotPermission::where('user_id',$member->user_id)->whereIn('bot_id',$diff->all())->where('high',true)->update(['high' => false]);
        } else {

            $diff = collect($bot_high)->diff($bots_of_user_old)->toArray();
            foreach ($diff as $bot_id) {
                BotPermission::updateOrCreate(['bot_id' => $bot_id, 'user_id' => $user->id],['high' => true]);
            }
        }

        // ------------------------------------------------------------------------------------------------------------

        $schedules = Hub::findOrFail(session('currentHub'))->schedules()->orderBy('id','DESC')->get();

        $scheduleperms = SchedulePermission::where('user_id',$member->user_id)->whereIn('schedule_id',array_pluck($schedules,'id'))->get();

        $schedules_of_user_old = array_pluck($scheduleperms,'schedule_id');

        if (count($schedules_of_user_old) > count($schedule_low)) {

            $diff = collect($schedules_of_user_old)->diff($schedule_low);
            SchedulePermission::where('user_id',$member->user_id)->whereIn('schedule_id',$diff->all())->delete();
        } else {

            $diff = collect($schedule_low)->diff($schedules_of_user_old)->toArray();
            foreach ($diff as $schedule_id) {
                $newPerm = new SchedulePermission;
                $newPerm->user_id     = $user->id;
                $newPerm->schedule_id = $schedule_id;
                $newPerm->save();
            }
        }

        $scheduleperms = SchedulePermission::where('user_id',$member->user_id)->whereIn('schedule_id',array_pluck($schedules,'id'))->where('high',true)->get();

        $schedules_of_user_old = array_pluck($scheduleperms,'schedule_id');

        if (count($schedules_of_user_old) > count($schedule_high)) {

            $diff = collect($schedules_of_user_old)->diff($schedule_high);
            SchedulePermission::where('user_id',$member->user_id)->whereIn('schedule_id',$diff->all())->where('high',true)->update(['high' => false]);
        } else {

            $diff = collect($schedule_high)->diff($schedules_of_user_old)->toArray();
            foreach ($diff as $schedule_id) {
                SchedulePermission::updateOrCreate(['schedule_id' => $schedule_id, 'user_id' => $user->id],['high' => true]);
            }
        }

        // ------------------------------------------------------------------------------------------------------------

        $events = Hub::findOrFail(session('currentHub'))->events()->orderBy('id','DESC')->get();

        $eventperms = EventPermission::where('user_id',$member->user_id)->whereIn('event_id',array_pluck($events,'id'))->get();

        $events_of_user_old = array_pluck($eventperms,'event_id');

        if (count($events_of_user_old) > count($event_low)) {

            $diff = collect($events_of_user_old)->diff($event_low);
            EventPermission::where('user_id',$member->user_id)->whereIn('event_id',$diff->all())->delete();
        } else {

            $diff = collect($event_low)->diff($events_of_user_old)->toArray();
            foreach ($diff as $event_id) {
                $newPerm = new EventPermission;
                $newPerm->user_id  = $user->id;
                $newPerm->event_id = $event_id;
                $newPerm->save();
            }
        }

        $eventperms = EventPermission::where('user_id',$member->user_id)->whereIn('event_id',array_pluck($events,'id'))->where('high',true)->get();

        $events_of_user_old = array_pluck($eventperms,'event_id');

        if (count($events_of_user_old) > count($event_high)) {

            $diff = collect($events_of_user_old)->diff($event_high);
            EventPermission::where('user_id',$member->user_id)->whereIn('event_id',$diff->all())->where('high',true)->update(['high' => false]);
        } else {

            $diff = collect($event_high)->diff($events_of_user_old)->toArray();
            foreach ($diff as $event_id) {
                EventPermission::updateOrCreate(['event_id' => $event_id, 'user_id' => $user->id],['high' => true]);
            }
        }

        // ------------------------------------------------------------------------------------------------------------

        $automations = Hub::findOrFail(session('currentHub'))->automations()->orderBy('id','DESC')->get();

        $automationperms = AutomationPermission::where('user_id',$member->user_id)->whereIn('automation_id',array_pluck($automations,'id'))->get();

        $automations_of_user_old = array_pluck($automationperms,'automation_id');

        if (count($automations_of_user_old) > count($automation_low)) {

            $diff = collect($automations_of_user_old)->diff($automation_low);
            AutomationPermission::where('user_id',$member->user_id)->whereIn('automation_id',$diff->all())->delete();
        } else {

            $diff = collect($automation_low)->diff($automations_of_user_old)->toArray();
            foreach ($diff as $automation_id) {
                $newPerm = new AutomationPermission;
                $newPerm->user_id       = $user->id;
                $newPerm->automation_id = $automation_id;
                $newPerm->save();
            }
        }

        $automationperms = AutomationPermission::where('user_id',$member->user_id)->whereIn('automation_id',array_pluck($automations,'id'))->where('high',true)->get();

        $automations_of_user_old = array_pluck($automationperms,'automation_id');

        if (count($automations_of_user_old) > count($automation_high)) {

            $diff = collect($automations_of_user_old)->diff($automation_high);
            AutomationPermission::where('user_id',$member->user_id)->whereIn('automation_id',$diff->all())->where('high',true)->update(['high' => false]);
        } else {

            $diff = collect($automation_high)->diff($automations_of_user_old)->toArray();
            foreach ($diff as $automation_id) {
                AutomationPermission::updateOrCreate(['automation_id' => $automation_id, 'user_id' => $user->id],['high' => true]);
            }
        }

        // ------------------------------------------------------------------------------------------------------------

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

    /**
     * Deactivate a member
     * @param  int    $id
     * @return Illuminate\Http\JsonResponse
     */
    public function deactivate(int $id) {
        $mem = Member::findOrFail($id)->deactivate();
        return response()->json(['error' => 0]);
    }

    /**
     * Reactivate a member
     * @param  int    $id
     * @return Illuminate\Http\JsonResponse
     */
    public function reactivate(int $id) {
        $mem = Member::findOrFail($id)->reactivate();
        return response()->json(['error' => 0]);
    }

    /**
     * Delete a member
     * @param  int    $id
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(int $id) {
        Member::destroy($id);
        return response()->json(['error' => 0]);
    }
}
