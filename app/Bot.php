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
    public function permissions() {
        return $this->hasMany('SmartBots\Permission','bot_id');
    }
    public function users() {
        // thông qua permission
        $permissions = $this->permissions;
        $users = [];
        foreach ($permissions as $permission) {
            $users[] = $permissions->user;
        }
        return collect($users);
    }
    public function isOf($hub_id) {
        return $hub_id == $this->hub->id;
    }
    public function canBeManagedBy($user_id) {
        return in_array($user_id, array_pluck($this->permissions->toArray(),'user_id'));
    }
}
