<?php

namespace SmartBots\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use SmartBots\User;
use SmartBots\Schedule;
use SmartBots\SchedulePermission;

class SchedulePolicy
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

    public function low(User $user, Schedule $schedule) {
        return $user->member($schedule->hub->id)->isActivated() && ($user->can('viewAllSchedules',$schedule->hub) || SchedulePermission::where('user_id',$user->id)->where('schedule_id',$schedule->id)->exists());
    }

    public function high(User $user, Schedule $schedule) {
        return $user->member($schedule->hub->id)->isActivated() && ($user->can('editDeleteAllSchedules',$schedule->hub) || SchedulePermission::where('user_id',$user->id)->where('schedule_id',$schedule->id)->where('high',1)->exists());
    }
}
