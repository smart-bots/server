<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class AutomationPermission extends Model
{
    protected $table = 'automationpermissions';

    protected $fillable = ['user_id','automation_id','high'];

    protected $hidden = [];

    public $timestamps = false;

    public function automation() {
        return $this->belongsTo('SmartBots\Automation','automation_id');
    }

    public function user() {
        return $this->belongsTo('SmartBots\User','user_id');
    }
}
