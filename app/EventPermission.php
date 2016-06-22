<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class EventPermission extends Model
{
    protected $table = 'eventpermissions';

    protected $fillable = ['user_id','event_id','high'];

    protected $hidden = [];

    public $timestamps = false;

    public function event() {
        return $this->belongsTo('SmartBots\Event','event_id');
    }

    public function user() {
        return $this->belongsTo('SmartBots\User','user_id');
    }
}
