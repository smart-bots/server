<?php

namespace SmartBots\Events;

use SmartBots\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestBroadcasting extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->data = array(
            'test-data'=> 'trollololololol'
        );
    }

    /**
     * Get the channels the event should be broadcast on (Redis sucscribe to).
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['test-channel'];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'test-event';
    }

    /*
        channel:event
     */
}
