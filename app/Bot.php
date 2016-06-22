<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

use SmartBots\Event;

class Bot extends Model
{
    protected $table = 'bots';

    protected $fillable = ['hub_id','name','token','image','description','type','status','true'];

    protected $hidden = [];

    public $timestamps = false;

    public function hub() {
        return $this->belongsTo('SmartBots\Hub','hub_id');
    }

    public function botpermissions() {
        return $this->hasMany('SmartBots\BotPermission','bot_id');
    }

    public function events() {
        return $this->hasMany('SmartBots\Event','trigger_bot');
    }

    public function users() {
        $botpermissions = $this->botpermissions;
        $users = [];
        foreach ($botpermissions as $botpermission) {
            $users[] = $botpermissions->user;
        }
        return collect($users);
    }

    public function isOf($hub_id) {
        return $hub_id == $this->hub->id;
    }

    public function isActivated():bool {
        return ($this->status != -1);
    }

    public function scopeActivated($query) {
        return $query->where('status','!=',-1);
    }

    public function control($state)
    {
        if ($this->status != -1) {
            $this->status = $state;
            $this->true = false;
            $this->save();

            if ($this->status == true) {
                $state_trigger = 2;
            } else {
                $state_trigger = 3;
            }

            $events = $this->events()->where('trigger_type',1)->orWhere('trigger_type',$state_trigger)->get();
            foreach ($events as $event) {
                $event->fire();
            }

            $automations = Automation::where('trigger_id',$this->id)->where(function ($query) use ($state_trigger) {
                $query->where('trigger_type',1)->orWhere('trigger_type',$state_trigger);
            })->get();
            foreach ($automations as $automation) {
                $automation->fire();
            }
        }
    }

    public function toggle()
    {
        if ($this->status != -1) {
            if ($this->status == 1) {
                $this->status = 0;
            } else if ($this->status == 0) {
                $this->status = 1;
            }

            $this->true = false;
            $this->save();

            if ($this->status == true) {
                $state_trigger = 2;
            } else {
                $state_trigger = 3;
            }

            $events = $this->events()->where('trigger_type',1)->orWhere('trigger_type',$state_trigger)->get();
            foreach ($events as $event) {
                $event->fire();
            }

            $automations = Automation::where('trigger_id',$this->id)->where(function ($query) use ($state_trigger) {
                $query->where('trigger_type',1)->orWhere('trigger_type',$state_trigger);
            })->get();
            foreach ($automations as $automation) {
                $automation->fire();
            }
        }
    }

    public function deactivate()
    {
        $this->status = -1;
        $this->save();
    }

    public function reactivate()
    {
        $this->status = 0;
        $this->save();
    }

    public function realStatus()
    {
        if ($this->true = false) {
            return 2;
        } else {
            return $this->status;
        }
    }
}
