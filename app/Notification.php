<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

use SmartBots\Events\BroadcastNotification;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable   = ['user_id', 'hub_id', 'subject', 'body', 'href', 'holder', 'read', 'created_at'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('SmartBots\User','user_id');
    }

    public function hub()
    {
        return $this->belongsTo('SmartBots\Hub','hub_id');
    }

    public function isRead():boolean
    {
        return $this->read;
    }

    public function scopeRead($query)
    {
        return $query->where('read',1);
    }

    public function read()
    {
        if ($this->read == 0) {
            $this->read = 1;
            $this->save();
            event(new BroadcastNotification($this->id,'read'));
        }
    }

    public static function send($data) {
        $newNoti = Notification::create($data);
        event(new BroadcastNotification($newNoti->id,'new'));
    }
}
