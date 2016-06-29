<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class EventPermission extends Model
{
    protected $table = 'eventpermissions';

    protected $fillable = ['user_id','event_id','high','notice'];

    protected $hidden = [];

    public $timestamps = false;

    public function event() {
        return $this->belongsTo('SmartBots\Event','event_id');
    }

    public function user() {
        return $this->belongsTo('SmartBots\User','user_id');
    }

    public function willNotice():boolean {
        return $this->notice;
    }
}
