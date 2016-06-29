<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class BotPermission extends Model
{
    protected $table = 'botpermissions';

    protected $fillable = ['user_id','bot_id','high','notice'];

    protected $hidden = [];

    public $timestamps = false;

    public function bot() {
        return $this->belongsTo('SmartBots\Bot','bot_id');
    }

    public function user() {
        return $this->belongsTo('SmartBots\User','user_id');
    }

    public function willNotice():boolean {
        return $this->notice;
    }
}
