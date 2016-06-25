<?php

namespace SmartBots;

use Illuminate\Database\Eloquent\Model;

class TwilioNumber extends Model
{
    protected $table = 'twilionumbers';
    protected $fillable = ['sid', 'number'];

    public $timestamps = false;

    public static function getRandomNumber()
    {
        $numbers = self::get()->lists('number')->all();
        return $numbers[array_rand($numbers)];
    }
}
