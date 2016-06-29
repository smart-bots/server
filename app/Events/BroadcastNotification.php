<?php

namespace SmartBots\Events;

use SmartBots\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use SmartBots\Notification;

use Carbon;

class BroadcastNotification extends Event implements ShouldBroadcast
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
    public function __construct($noti_id,$method)
    {
        $this->method = $method;

        $noti = Notification::findOrFail($noti_id);

        $this->user_id = $noti->user_id;
        $this->hub_id = $noti->hub_id;

        if ($method == 'new') {

            $this->broadcastWith = [
                'id'      => $noti->id,
                'user_id' => $noti->user_id,
                'hub_id'  => $noti->hub_id,
                'subject' => $noti->subject,
                'body'    => $noti->body,
                'href'    => $noti->href,
                'created_at' => Carbon::parse($noti->created_at)->timestamp
            ];

            $holder_data = explode(':',$noti->holder);

            if ($holder_data[0] == 'image') {
                $this->broadcastWith['holder'] = 'image:'.@asset($holder_data[1]);
            } else {
                $this->broadcastWith['holder'] = $noti->holder;
            }

        } else if ($method == 'read') {

            $this->broadcastWith = [
                'id' => $noti->id
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
        return ['notification:'.$this->method];
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
}
