<?php

namespace SmartBots\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use SmartBots\User;
use SmartBots\Bot;
use SmartBots\BotPermission;

class BotPolicy
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

    /**
     * Check if user has a low permission with the given bot
     * @param  User   $user
     * @param  Bot    $bot
     * @return boolean
     */
    public function low(User $user, Bot $bot) {
        return $user->member($bot->hub->id)->isActivated() && ($user->can('viewControlAllBots',$bot->hub) || BotPermission::where('user_id',$user->id)->where('bot_id',$bot->id)->exists());
    }

    /**
     * Check if user has a high permission with the given bot
     * @param  User   $user
     * @param  Bot    $bot
     * @return boolean
     */
    public function high(User $user, Bot $bot) {
        return $user->member($bot->hub->id)->isActivated() && ($user->can('editDeleteAllBots',$bot->hub) || BotPermission::where('user_id',$user->id)->where('bot_id',$bot->id)->where('high',1)->exists());
    }
}
