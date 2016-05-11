<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class Hub extends Model
{
    protected $table = 'hubs';
    protected $fillable = ['token','name','image','description','status'];
    protected $hidden = [];
    public $timestamps = false;
    public function bots() {
    	return $this->hasMany('SmartBots\Bot','hub_id');
    }
    public function members() {
    	return $this->hasMany('SmartBots\Member','hub_id');
    }
    public function highpermissions() {
        return $this->hasMany('SmartBots\HighPermission','hub_id');
    }
    public function schedules() {
        return $this->hasMany('SmartBots\Schedule','hub_id');
    }
    public function users() {
        return $this->belongsToMany('SmartBots\User','members');
    }
    public function isActivated() {
    	return $this->status == 1 ? true : false;
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
}
