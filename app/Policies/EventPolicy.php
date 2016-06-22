<?php

namespace SmartBots\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use SmartBots\User;
use SmartBots\Event;
use SmartBots\EventPermission;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function low(User $user, Event $event) {
        return $user->member($event->hub->id)->isActivated() && ($user->can('viewAllEvents',$event->hub) || EventPermission::where('user_id',$user->id)->where('event_id',$event->id)->exists());
    }

    public function high(User $user, Event $event) {
        return $user->member($event->hub->id)->isActivated() && ($user->can('editDeleteAllEvents',$event->hub) || EventPermission::where('user_id',$user->id)->where('event_id',$event->id)->where('high',1)->exists());
    }
}
