<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class Automation extends Model
{
    protected $table = 'automations';

    protected $fillable = ['hub_id','name', 'description','trigger_type','trigger_id','action','condition','activate_after','deactivate_after','status','ran_times'];

    protected $hidden = [];

    public $timestamps = false;

    public function automationpermissions() {
        return $this->hasMany('SmartBots\AutomationPermission','automation_id');
    }

    public function hub() {
        return $this->belongsTo('SmartBots\Hub','hub_id');
    }

    public function users() {
        $automationpermissions = $this->automationpermissions;
        $users = [];
        foreach ($automationpermissions as $automationpermission) {
            $users[] = $automationpermissions->user;
        }
        return collect($users);
    }

    public function isOf($hub_id) {
        return $hub_id == $this->hub->id;
    }

    public function isActivated():bool {
        return $this->status;
    }

    public function deactivate()
    {
        $this->status = 0;
        $this->save();
    }

    public function reactivate()
    {
        $this->status = 1;
        $this->save();
    }

    public function scopeActivated($query) {
        return $query->where('status',1);
    }

    public function fire() {

        if ($this->isActivated()) {

            if ($this->condition_type != 0) {

                $this_conditions = explode('|',$this->condition_data);

                for ($i=0; $i<count($this_conditions); $i++) {

                    $this_conditions[$i] = explode(',',$this_conditions[$i]);
                    $return_conditions[] = Bot::findOrFail($this_conditions[$i][0])->realStatus() == $this_conditions[$i][1];

                }

                if ($this->condition_method == 1) {

                    $return_condition = true;

                    foreach ($return_conditions as $single_return_condition) {

                        if ($single_return_condition == false) {
                            $return_condition2 = false;
                            break;
                        }

                    }

                } else if ($this->condition_method == 2) {

                    $return_condition = false;

                    foreach ($return_conditions as $single_return_condition) {

                        if ($single_return_condition == true) {
                            $return_condition = true;
                            break;
                        }
                    }
                }

                if (($return_condition == true && $this->condition_type == 1)
                || ($return_condition == false && $this->condition_type == 2)) {
                    $run_automation = true;
                } else {
                    $run_automation = false;
                }

            } else {
                $run_automation = true;
            }

            if ($run_automation == true) {

                $this_actions = explode('|',$this->action);

                for($i=0; $i<count($this_actions); $i++) {

                    $this_actions[$i] = explode(',',$this_actions[$i]);

                    switch ($this_actions[$i][0]) {
                        case 1:
                            Bot::findOrFail($this_actions[$i][1])->toggle();
                            break;
                        case 2:
                            Bot::findOrFail($this_actions[$i][1])->control(1);
                            break;
                        case 3:
                            Bot::findOrFail($this_actions[$i][1])->control(0);
                            break;
                    }

                }

                $this->ran_times++;

                $this->save();

            }
        }
    }
}
