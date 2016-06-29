<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Http\Request;

use SmartBots\Http\Requests;

use SmartBots\{
    Hub,
    Automation,
    AutomationPermission
};

use Validator;

class AutomationController extends Controller
{
    /**
     * Listing all automation that user can see
     * @return Illuminate\Contracts\View\View
     */
    public function index() {

        if (auth()->user()->can('viewAllAutomations',Hub::findOrFail(session('currentHub')))) {
            $automations = Hub::findOrFail(session('currentHub'))->automations()->orderBy('id','DESC')->get();
        } else {
            $automations = auth()->user()->automationsOf(session('currentHub'))->sortByDesc('id');
        }

        return view('hub.automation.index')->withAutomations($automations);
    }

    /**
     * Show up the automation create form
     * @return Illuminate\Contracts\View\View
     */
    public function create() {

        $users = Hub::findOrFail(session('currentHub'))->users()->orderBy('id','DESC')->get();

        $nUsers = [];
        foreach ($users as $user) {
            $nUsers[$user['id']] = $user['username'];
        }

        return view('hub.automation.create')->withUsers($nUsers);
    }

    /**
     * Handle the request to create new automation
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function store(Request $request) {

        $rules = [
            'name'              => 'required|max:100',
            'description'       => 'max:1000',
            'trigger.type'      => 'required|numeric|between:1,4',
            'action.type.0'     => 'required|numeric|between:1,3',
            'action.bot.0'      => 'required|numeric|exists:bots,id', // Thiếu check permission
            'action.type.*'     => 'numeric|between:1,3',
            'action.bot.*'      => 'numeric|exists:bots,id',
            'condition.type'    => 'required|numeric|between:0,2',
            'permissions.*'     => 'exists:members,user_id,hub_id,'.session('currentHub'),
            'highpermissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub')
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) { return response()->json($validator->errors()); }

        if ($request->trigger['type'] == 4) {

            $rules = ['trigger.id' => 'required|exists:events,id'];
        } else {

            $rules = ['trigger.id' => 'required|exists:bots,id'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) { return response()->json($validator->errors()); }

        if ($request->condition['type'] != 0) {

            $rules = [
                'condition.method'  => 'required|numeric|between:1,2',
                'condition.bot.0'   => 'required|exists:bots,id',
                'condition.state.0' => 'required|numeric|between:0,1',
                'condition.bot.*'   => 'required|exists:bots,id',
                'condition.state.*' => 'required|numeric|between:0,1'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) { return response()->json($validator->errors()); }
        }

        $automation = new Automation;

        $automation->hub_id      = session('currentHub');
        $automation->name        = $request->name;
        $automation->description = $request->description;

        $actionData = '';
        for ($i=0;$i<count($request->action['type']);$i++) {
            $actionData .= $request->action['type'][$i].','.$request->action['bot'][$i].'|';
        }
        $automation->action = trim($actionData,'|');

        $automation->trigger_type = $request->trigger['type'];
        $automation->trigger_id   = $request->trigger['id'];

        if ($request->condition['type'] != 0)
        {
            $automation->condition_type = $request->condition['type'];
            $automation->condition_method = $request->condition['method'];

            $conditionData = '';
            for ($i=0;$i<count($request->condition['bot']);$i++) {
                $conditionData .= $request->condition['bot'][$i].','.$request->condition['state'][$i].'|';
            }
            $automation->condition_data = trim($conditionData,'|');
            dd($automation->condition_data);
        }
        $automation->status = 1;
        $automation->save();

        $newAutomationPerm = new AutomationPermission;
        $newAutomationPerm->user_id       = auth()->user()->id;
        $newAutomationPerm->automation_id = $automation->id;
        $newAutomationPerm->high          = true;
        $newAutomationPerm->notice        = $request->notice;
        $newAutomationPerm->save();

        if (is_array($request->permissions)) {
            foreach ($request->permissions as $user_id) {
                $newPerm                = new AutomationPermission;
                $newPerm->automation_id = $automation->id;
                $newPerm->user_id       = $user_id;
                $newPerm->save();
            }
        }

        if (is_array($request->highpermissions)) {
            foreach ($request->highpermissions as $user_id) {
                AutomationPermission::updateOrCreate(['automation_id' => $automation->id, 'user_id' => $user_id],['high' => true]);
            }
        }

        $errors = ['success' => 'true', 'href' => route('h::a::index')];
        return response()->json($errors);

    }

    /**
     * Show up the automation edit form
     * @param  int $id
     * @return Illuminate\Contracts\View\View
     */
    public function edit(int $id) {
        $automation = Automation::findOrFail($id);
        $users = Hub::findOrFail(session('currentHub'))->users()->orderBy('id','DESC')->get()->toArray();

        $nUsers = [];
        foreach ($users as $user) {
            $nUsers[$user['id']] = $user['username'];
        }

        $perms = AutomationPermission::where('automation_id',$id)->get();
        $sUsers = array_pluck($perms,'user_id');

        $perm2s = AutomationPermission::where('automation_id',$id)->where('high',true)->get();
        $sUser2s = array_pluck($perm2s,'user_id');

        return view('hub.automation.edit')
            ->withAutomation($automation)
            ->withUsers($nUsers)
            ->withSelected($sUsers)
            ->withSelected2($sUser2s);
    }

    /**
     * Handle the request to edit a automation
     * @param  int     $id
     * @param  Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function update(int $id, Request $request) {

        $rules = [
            'permissions.*'     => 'exists:members,user_id,hub_id,'.session('currentHub'),
            'highpermissions.*' => 'exists:members,user_id,hub_id,'.session('currentHub')
        ];

        $automationperms         = AutomationPermission::where('automation_id',$id)->get();
        $users_of_automation_old = array_pluck($automationperms,'user_id');

        if (count($users_of_automation_old) > count($request->permissions)) { // Xóa bớt

            $diff = collect($users_of_automation_old)->diff($request->permissions);
            AutomationPermission::whereIn('user_id',$diff->all())->where('automation_id',$id)->delete();

        } else { // Hoặc thêm

            $diff = collect($request->permissions)->diff($users_of_automation_old)->toArray();
            foreach ($diff as $user_id)
            {
                $newPerm = new AutomationPermission;
                $newPerm->user_id       = $user_id;
                $newPerm->automation_id = $id;
                $newPerm->save();
            }
        }

        $automationperms = AutomationPermission::where('automation_id',$id)->where('high',true)->get();
        $users_of_automation_old = array_pluck($automationperms,'user_id');

        if (count($users_of_automation_old) > count($request->highpermissions)) { // Xóa bớt

            $diff = collect($users_of_automation_old)->diff($request->highpermissions);
            AutomationPermission::whereIn('user_id',$diff->all())->where('automation_id',$id)->where('high',true)->update(['high' => false]);

        } else { // Hoặc thêm

            $diff = collect($request->highpermissions)->diff($users_of_automation_old)->toArray();
            foreach ($diff as $user_id)
            {
                AutomationPermission::updateOrCreate(['automation_id' => $id, 'user_id' => $user_id],['high' => true]);
            }
        }

        AutomationPermission::updateOrCreate(['automation_id' => $id, 'user_id' => auth()->user()->id],['notice' => $request->notice]);

        $errors = ['success' => 'Saved successfully'];
        return response()->json($errors);
    }

    /**
     * Deactivate a automation
     * @param  int $id
     * @return Illuminate\Http\JsonResponse
     */
    public function deactivate(int $id) {

        Automation::findOrFail($id)->deactivate();
        return response()->json(['error' => 0]);
    }

    /**
     * Reactivate a automation
     * @param  int $id
     * @return Illuminate\Http\JsonResponse
     */
    public function reactivate(int $id) {

        Automation::findOrFail($id)->reactivate();
        return response()->json(['error' => 0]);
    }

    /**
     * Delete a automation
     * @param  int $id
     * @return Illuminate\Http\JsonResponse
     */
    public function destroy(int $id) {

        Automation::destroy($id);
        return response()->json(['error' => 0]);
    }
}
