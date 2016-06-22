<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class HubPermission extends Model
{
    protected $table = 'hubpermissions';

    protected $fillable = ['user_id','hub_id','data'];

    protected $hidden = [];

    public $timestamps = false;

    public function user() {
        return $this->belongsTo('SmartBots\User','user_id');
    }
    public function hub() {
        return $this->belongsTo('SmartBots\Hub','hub_id');
    }
}
