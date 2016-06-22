<?php

namespace SmartBots;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = ['username','name', 'email','phone','password','avatar'];

    protected $hidden = ['password', 'remember_token'];

    public $timestamps = false;

    public function members() {
        return $this->hasMany('SmartBots\Member','user_id');
    }

    public function botpermissions() {
        return $this->hasMany('SmartBots\BotPermission','user_id');
    }

    public function schedulepermissions() {
        return $this->hasMany('SmartBots\SchedulePermission','user_id');
    }

    public function automationpermissions() {
        return $this->hasMany('SmartBots\AutomationPermission','user_id');
    }

    public function eventpermissions() {
        return $this->hasMany('SmartBots\EventPermission','user_id');
    }

    public function hubpermissions() {
        return $this->hasMany('SmartBots\HubPermission','user_id');
    }

    public function hubs() {
        return $this->belongsToMany('SmartBots\Hub','members');
    }

    public function bots() {
        $botpermissions = $this->botpermissions;
        $bots = collect([]);
        foreach ($botpermissions as $botpermission) {
            $bots = $bots->push($botpermission->bot);
        }
        return $bots;
    }

    public function schedules() {
        $schedulepermissions = $this->schedulepermissions;
        $schedules = collect([]);
        foreach ($schedulepermissions as $schedulepermission) {
            $schedules = $schedules->push($schedulepermission->schedule);
        }
        return $schedules;
    }

    public function automations() {
        $automationpermissions = $this->automationpermissions;
        $automations = collect([]);
        foreach ($automationpermissions as $automationpermission) {
            $automations = $automations->push($automationpermission->automation);
        }
        return $automations;
    }

    public function events() {
        $eventpermissions = $this->eventpermissions;
        $events = collect([]);
        foreach ($eventpermissions as $eventpermission) {
            $events = $events->push($eventpermission->event);
        }
        return $events;
    }

    public function botsOf($hub_id) {
        $botpermissions = $this->botpermissions;
        $bots = collect([]);
        foreach ($botpermissions as $botpermission) {
            if ($botpermission->bot->isOf($hub_id)) {
                $bots = $bots->push($botpermission->bot);
            }
        }
        return $bots;
    }

    public function schedulesOf($hub_id) {
        $schedulepermissions = $this->schedulepermissions;
        $schedules = collect([]);
        foreach ($schedulepermissions as $schedulepermission) {
            if ($schedulepermission->schedule->isOf($hub_id)) {
                $schedules = $schedules->push($schedulepermission->schedule);
            }
        }
        return $schedules;
    }

    public function automationsOf($hub_id) {
        $automationpermissions = $this->automationpermissions;
        $automations = collect([]);
        foreach ($automationpermissions as $automationpermission) {
            if ($automationpermission->automation->isOf($hub_id)) {
                $automations = $automations->push($automationpermission->automation);
            }
        }
        return $automations;
    }

    public function eventsOf($hub_id) {
        $eventpermissions = $this->eventpermissions;
        $events = collect([]);
        foreach ($eventpermissions as $eventpermission) {
            if ($eventpermission->event->isOf($hub_id)) {
                $events = $events->push($eventpermission->event);
            }
        }
        return $events;
    }

    public function isOf($hub_id) {
        return in_array($hub_id, array_pluck($this->members->toArray(),'hub_id'));
    }

    public function member($hub_id) {
        if ($this->isOf($hub_id)) {
            $members = $this->members;
            foreach ($members as $member) {
                if ($member->hub_id == $hub_id) {
                    return $member;
                }
            }
        }
    }
}
