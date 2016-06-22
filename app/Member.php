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

    public function scopeActivated($query) {
        return $query->where('status',1);
    }

    public function deactivate()
    {
        $this->status = 0;
        $this->save();
    }

    public function reactivate()
    {
        $this->status = 1;
        $this->save();
    }
}
