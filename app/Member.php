<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

use SmartBots\User;
use SmartBots\Hub;
use SmartBots\Bot;
use SmartBots\Permission;
use SmartBots\HighPermission;

class Member extends Model
{
    protected $table = 'members';
    protected $fillable = ['hub_id','user_id','status'];
    protected $hidden = [];
    public $timestamps = false;
    public function hub() {
    	return $this->belongsTo('SmartBots\Hub','hub_id');
    }
    public function user() {
    	return $this->belongsTo('SmartBots\User','user_id');
    }
}
