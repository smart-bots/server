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

    /**
     * Check if user has a low permission with the given automation
     * @param  User       $user
     * @param  Automation $automation
     * @return boolean
     */
    public function low(User $user, Automation $automation) {
        return $user->member($automation->hub->id)->isActivated() && ($user->can('viewAllAutomations',$automation->hub) || AutomationPermission::where('user_id',$user->id)->where('automation_id',$automation->id)->exists());
    }

    /**
     * Check if user has a high permission with the given automation
     * @param  User       $user
     * @param  Automation $automation
     * @return boolean
     */
    public function high(User $user, Automation $automation) {
        return $user->member($automation->hub->id)->isActivated() && ($user->can('editDeleteAllAutomations',$automation->hub) || AutomationPermission::where('user_id',$user->id)->where('automation_id',$automation->id)->where('high',1)->exists());
    }
}
