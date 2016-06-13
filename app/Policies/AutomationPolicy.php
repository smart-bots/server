<?php

namespace SmartBots\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use SmartBots\User;
use SmartBots\Automation;
use SmartBots\AutomationPermission;

class AutomationPolicy
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

    public function basic(User $user, Automation $automation) {
        return $user->member($schedule->hub->id)->isActivated() && ($user->can('viewAllAutomations',$bot->hub) || AutomationPermission::where('user_id',$user->id)->where('automation_id',$automation->id)->exists());
    }

    public function higher(User $user, Automation $automation) {
        return $user->member($schedule->hub->id)->isActivated() && ($user->can('editDeleteAllAutomations',$bot->hub) || AutomationPermission::where('user_id',$user->id)->where('automation_id',$automation->id)->where('high',1)->exists());
    }
}
