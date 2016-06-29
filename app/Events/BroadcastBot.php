<?php

namespace SmartBots\Events;

use SmartBots\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use SmartBots\Bot;

class BroadcastBot extends Event implements ShouldBroadcast
{
    use SerializesModels;

    private $user_id;
    private $hub_id;
    private $method;
    private $broadcastWith;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($bot_id,$user_id,$hub_id,$method,$state)
    {
        $this->user_id = $user_id;
        $this->hub_id  = $hub_id;
        $this->method  = $method;

        if ($method == 'change') {
            $this->broadcastWith = [
                'id'    => $bot_id,
                'state' => $state
            ];
        }
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return $this->broadcastWith;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        // add, remove, change
        return ['bot:'.$this->method];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return $this->user_id.'.'.$this->hub_id;
    }

    // socket join room "<user_id>:<hub_id>" and subscribe to "bot:<method>"
}
