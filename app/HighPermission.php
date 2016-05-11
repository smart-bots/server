<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

use SmartBots\User;
use SmartBots\Hub;
use SmartBots\Member;
use SmartBots\Bot;
use SmartBots\Permission;

class HighPermission extends Model
{
    protected $table = 'highpermissions';
    protected $fillable = ['user_id','hub_id','data'];
    protected $hidden = [];
    public $timestamps = false;
}
