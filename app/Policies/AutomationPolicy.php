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
        return $user->can('viewAllAutomations',$bot->hub) || AutomationPermission::where('user_id',$user->id)->where('automation_id',$automation->id)->exists();
    }

    public function higher(User $user, Automation $automation) {
        return $user->can('editDeleteAllAutomations',$bot->hub) || AutomationPermission::where('user_id',$user->id)->where('automation_id',$automation->id)->where('higher',1)->exists();
    }
}
