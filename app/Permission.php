<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

use SmartBots\User;
use SmartBots\Hub;
use SmartBots\Member;
use SmartBots\Bot;
use SmartBots\HighPermission;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['user_id','bot_id'];
    protected $hidden = [];
    public $timestamps = false;
    public function bot() {
    	return $this->belongsTo('SmartBots\Bot','bot_id');
    }
    public function user() {
    	return $this->belongsTo('SmartBots\User','user_id');
    }
}
