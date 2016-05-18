<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class Automation extends Model
{
    protected $table = 'automations';
    protected $fillable = ['hub_id','name', 'description','trigger','action','type','data','condition','activate_after','deactivate_after','status','ran_times'];
    protected $hidden = [];
    public $timestamps = false;

    public function user() {
        return $this->belongsTo('SmartBots\User','user_id');
    }

    public function automationpermissions() {
        return $this->hasMany('SmartBots\AutomationPermission','automation_id');
    }

    public function hub() {
        return $this->belongsTo('SmartBots\Hub','hub_id');
    }

    public function users() {
        // thông qua automationpermission
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
}
