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

    public function basic(User $user, Bot $bot) {
        return $user->can('viewControlAllBots',$bot->hub) || BotPermission::where('user_id',$user->id)->where('bot_id',$bot->id)->exists();
    }

    public function higher(User $user, Bot $bot) {
        return $user->can('editDeleteAllBots',$bot->hub) || BotPermission::where('user_id',$user->id)->where('bot_id',$bot->id)->where('higher',1)->exists();
    }
}
