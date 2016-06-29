<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class SchedulePermission extends Model
{
    protected $table = 'schedulepermissions';

    protected $fillable = ['user_id','schedule_id','high','notice'];

    protected $hidden = [];

    public $timestamps = false;

    public function schedule() {
        return $this->belongsTo('SmartBots\Schedule','schedule_id');
    }

    public function user() {
        return $this->belongsTo('SmartBots\User','user_id');
    }

    public function willNotice():boolean {
        return $this->notice;
    }
}
