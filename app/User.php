<?php

namespace SmartBots;

use Illuminate\Foundation\Auth\User as Authenticatable;

use SmartBots\User;
use SmartBots\Hub;
use SmartBots\Member;
use SmartBots\Bot;
use SmartBots\BotPermission;
use SmartBots\Schedule;
use SmartBots\SchedulePermission;
use SmartBots\HighPermission;

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
        return $this->hasMany('SmartBots\BotPermission','user_id');
    }

    public function schedules() {
        return $this->hasMany('SmartBots\Schedule','user_id');
    }

    public function highpermissions() {
        return $this->hasMany('SmartBots\HighPermission','user_id');
    }

    public function hubs() {
        return $this->belongsToMany('SmartBots\Hub','members');
    }

    public function bots() {
        $botpermissions = $this->botpermissions;
        foreach ($botpermissions as $botpermission) {
            if (!isset($bots)) {
                $bots = $botpermission->bots;
            } else {
                $bots = $bots->merge($botpermission->bots);
            }
        }
        return $bots;
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

    public function canControlId($bot_id) {
        return in_array($bot_id, array_pluck($this->bots()->toArray(),'id'));
    }

    public function canControlToken($bot_token) {
        return in_array($bot_token, array_pluck($this->bots()->toArray(),'token'));
    }
}
