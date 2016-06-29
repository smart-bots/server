<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class Hub extends Model
{
    protected $table = 'hubs';

    protected $fillable = ['token','name','image','description','status','timezone'];

    protected $hidden = [];

    public $timestamps = false;

    public function hubpermissions() {
        return $this->hasMany('SmartBots\HubPermission','hub_id');
    }

    public function bots() {
        return $this->hasMany('SmartBots\Bot','hub_id');
    }

    public function members() {
        return $this->hasMany('SmartBots\Member','hub_id');
    }

    public function schedules() {
        return $this->hasMany('SmartBots\Schedule','hub_id');
    }

    public function automations() {
        return $this->hasMany('SmartBots\Automation','hub_id');
    }

    public function events() {
        return $this->hasMany('SmartBots\Event','hub_id');
    }

    public function notifications() {
        return $this->hasMany('SmartBots\Notification','hub_id');
    }

    public function quickcontrol() {
        return $this->hasMany('SmartBots\QuickControl','hub_id');
    }

    public function users() {
        return $this->belongsToMany('SmartBots\User','members');
    }

    public function isActivated():bool {
        return $this->status;
    }

    public function scopeActivated($query) {
        return $query->where('status',1);
    }

    public function hasUser($user_id) {
        return in_array($user_id, array_pluck($this->members->toArray(),'user_id'));
    }

    public function hasBotId($bot_id) {
        return in_array($bot_id, array_pluck($this->bots->toArray(),'id'));
    }

    public function hasBotToken($bot_token) {
        return in_array($bot_token, array_pluck($this->bots->toArray(),'token'));
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
}
