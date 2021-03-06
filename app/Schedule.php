<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $fillable = ['hub_id','name', 'description','action','type','data','condition','activate_after','deactivate_after','status','ran_times','next_run_time'];

    protected $hidden = [];

    public $timestamps = false;

    public function user() {
        return $this->belongsTo('SmartBots\User','user_id');
    }

    public function schedulepermissions() {
        return $this->hasMany('SmartBots\SchedulePermission','schedule_id');
    }

    public function hub() {
        return $this->belongsTo('SmartBots\Hub','hub_id');
    }

    public function users() {
        // thông qua schedulepermission
        $schedulepermissions = $this->schedulepermissions;
        $users = [];
        foreach ($schedulepermissions as $schedulepermission) {
            $users[] = $schedulepermissions->user;
        }
        return collect($users);
    }

    public function isOf($hub_id) {
        return $hub_id == $this->hub->id;
    }

    public function isActivated():bool {
        return $this->status;
    }

    public function isActivatedx():bool {
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
}
