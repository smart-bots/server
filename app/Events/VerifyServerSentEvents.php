<?php

namespace SmartBots\Events;

use Cache;

use Hoa\Eventsource\Server;

class VerifyServerSentEvents
{

    private $number;

    private $ttl = 10/ 60 *1;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($number)
    {
        $this->number = $number;
    }

    public function sendVerified()
    {
        Cache::tags($this->number)->put('event', 'verified', $this->ttl);
    }

    public function sendExpired()
    {
        Cache::tags($this->number)->put('event', 'expired', $this->ttl);
    }

    public function output() {

        $event = Cache::tags($this->number)->pull('event');

        $server = new Server();

        $server->$event->send('');

    }
}
