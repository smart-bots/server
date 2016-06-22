<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = ['hub_id','name','trigger','status'];

    protected $hidden = [];

    public $timestamps = false;

    public function user() {
        return $this->belongsTo('SmartBots\User','user_id');
    }

    public function eventpermissions() {
        return $this->hasMany('SmartBots\EventPermission','event_id');
    }

    public function hub() {
        return $this->belongsTo('SmartBots\Hub','hub_id');
    }

    public function bot() {
        return $this->belongsTo('SmartBots\Bot','trigger_bot');
    }

    public function users() {
        // thông qua eventpermission
        $eventpermissions = $this->eventpermissions;
        $users = [];
        foreach ($eventpermissions as $eventpermission) {
            $users[] = $eventpermissions->user;
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
        $automations = Automation::where('trigger_id',$this->id)->where('trigger_type',4)->get();
        foreach ($automations as $automation) {
            $automation->fire();
        }
    }
}
