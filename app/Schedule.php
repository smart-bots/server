<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
	protected $table = 'schedules';
    protected $fillable = ['member_id','name', 'description','action','type','data','condition','activate_after','deactivate_after'];
    protected $hidden = [];
    public $timestamps = false;
    public function user() {
    	return $this->belongsTo('SmartBots\User','user_id');
    }
    public function hub() {
    	return $this->belongsTo('SmartBots\Hub','hub_id');
    }
}
