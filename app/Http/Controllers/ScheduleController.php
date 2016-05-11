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
use SmartBots\Schedule;

class ScheduleController extends Controller
{
	public function index() {
		$schedules = Hub::findOrFail(session('currentHub'))->schedules()->orderBy('id','DESC')->get();
		return view('hub.schedule.index')->withSchedules($schedules);
	}

	public function create() {
		return view('hub.schedule.create');
	}

	public function store(Request $request) {
		// 	dd($request->toArray());

		$schedule = new Schedule;

		if (User::findOrFail(auth()->user()->id)->getMemberInfoOf(session('currentHub')) != true) {
			$schedule->member_id = User::findOrFail(auth()->user()->id)->getMemberInfoOf(session('currentHub'))->id;
		}

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
		} else {

			$frequencyData = '';
			for ($i=0;$i<count($request->frequency['value']);$i++) {
				$eachFrequency = $request->frequency['unit'][$i].','.$request->frequency['value'][$i].','.$request->frequency['at'][$i];
				trim($eachFrequency,',');
				$frequencyData .= $eachFrequency.'|';
			}
			$schedule->data = trim($frequencyData,'|');

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
		}

		$schedule->activate_after = $request->activate_after;
		$schedule->deactivate_after_times = $request->deactivate_after_times;
		$schedule->deactivate_after_datetime = $request->deactivate_after_datetime;

		$schedule->save();

		// dd($schedule->toArray());

		// $rules = [
		// 	'name' => 'required|max:100',
		// 	'description' => 'max:1000',
		// 	'action.type.0' => 'required|between:1,3',
		// 	'action.bot.0' => 'required|exists:bots,id', // Thiếu check permission
		// 	'action.type.*' => 'between:1,3',
		// 	'action.bot.*' => 'exists:id,bots',
		// 	'type' => 'required|between:1,2',
		// 	'condition.type' => 'required|between:1,3',
		// 	'activate_after' => 'before:'.time(),
		// 	'deactivate_after_times' => 'numeric',
		// 	'deactivate_after_datetime' => 'after:'.time(),
		// ];

		// $validator = Validator::make($request->all(), $rules);

		// if ($validator->fails()) {
		// 	return response()->json($validator->errors());
		// } else {
		// 	if (!$validator->errors()->has('type')) {
		// 		if ($request->type == 1) {
		// 			$rules = ['time' => 'required|after:'.time()];

		// 			$validator = Validator::make($request->all(), $rules);

		// 			if ($validator->fails()) {
		// 				return response()->json($validator->errors());
		// 			} else {

		// 			}
		// 		} else {
		// 			$rules = [
		// 				'frequency.unit.0' => 'required|between:1,6',
		// 				'frequency.unit.*' => 'between:1,6',
		// 			];

		// 			$validator = Validator::make($request->all(), $rules);

		// 			if ($validator->fails()) {
		// 				return response()->json($validator->errors());
		// 			} else {
		// 				$i=0;
		// 				$rules = [];
		// 				foreach ($request->frequency['unit'] as $unit) {
		// 					switch ($unit) {
		// 						case 1: // minutes
		// 							$rules['frequency.value.'.$i] = 'required|in:30,20,15,12,10,6,5,4,3,2,1';
		// 							break;
		// 						case 2: // hours
		// 							$rules['frequency.value.'.$i] = 'required|in:12,8,6,4,3,2,1';
		// 							$rules['frequency.at.'.$i] = 'required|between:0,59';
		// 							break;
		// 						case 3: // days
		// 							$rules['frequency.value.'.$i] = 'required|in:12,8,6,4,3,2,1';
		// 							$rules['frequency.at.'.$i] = 'required|between:0,59';
		// 							break;
		// 						case 4: // weeks
		// 							$rules['frequency.value.'.$i] = 'required|between:1,3';
		// 							$rules['frequency.at.'.$i] = ['required','regex:/^(Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/'];
		// 							break;
		// 						case 5: // months
		// 							$rules['frequency.value.'.$i] = 'required|in:6,4,4,2,1';
		// 							$rules['frequency.at.'.$i] = ['required','regex:/^([012]{1}[0-9]{1}|[3]{1}[01]) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/'];
		// 							break;
		// 						case 6: // years
		// 							$rules['frequency.value.'.$i] = 'required|numeric';
		// 							$rules['frequency.at.'.$i] = ['required','regex:/^(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Otc|Nov|Dec) ([012]{1}[0-9]{1}|[3]{1}[01]) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/'];
		// 							break;
		// 					}
		// 					$i++;
		// 				}

		// 				$validator = Validator::make($request->all(), $rules);

		// 				if ($validator->fails()) {
		// 					return response()->json($validator->errors());
		// 				} else {

		// 				}
		// 			}
		// 			return response()->json(['success' => true]);
		// 		}
		// 	}
		// }
	}
}
