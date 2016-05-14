<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

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

    public function isActivated():bool {
        return $this->status;
    }
}
