<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class QuickControl extends Model
{
    protected $table = 'quickcontrols';

    protected $fillable   = ['user_id', 'hub_id', 'data'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('SmartBots\User','user_id');
    }

    public function hub()
    {
        return $this->belongsTo('SmartBots\Hub','hub_id');
    }

    public function add($id)
    {
        $data = explode(',',$this->data);

        if (!in_array($id,$data)) {
            $data[] = $id;
        }

        $this->data = implode(',',$data);
        $this->save();
    }

    public function remove($id)
    {
        $data = explode(',',$this->data);

        if (in_array($id,$data)) {
            unset($data[array_search($id,$data)]);
        }

        $this->data = implode(',',$data);
        $this->save();
    }

    public function bots()
    {
        return Bot::whereIn('id',explode(',',$this->data))->get();
    }
}
