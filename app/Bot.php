<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

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

    public function canBeManagedBy($user_id) {
        return in_array($user_id, array_pluck($this->botpermissions->toArray(),'user_id'));
    }
}
