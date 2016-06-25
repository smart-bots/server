<?php

namespace SmartBots\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

use SmartBots\User;
use SmartBots\Hub;
use SmartBots\HubPermission;

class HubPolicy
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
     * Check if user has a right permission to do anything with this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function doAnything(User $user, Hub $hub) {
        return HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',0)->exists();
    }

    /**
     * Check if user has a right permission to view this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function view(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && ($this->editDelete($user,$hub)
                    || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',1)->exists()));
    }

    /**
     * Check if user has a right permission to edit/delete this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function editDelete(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',2)->exists());
    }

    /**
     * Check if user has a right permission to add new bot to this hub
     * @param User $user
     * @param Hub  $hub
     */
    public function addBots(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',3)->exists());
    }

    /**
     * Check if user has a right permission to view/control all bots in this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function viewControlAllBots(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && ($this->editDeleteAllBots($user,$hub)
                    || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',4)->exists()));
    }

    /**
     * Check if user has a right permission to edit/delete all bots in this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function editDeleteAllBots(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',5)->exists());
    }

    /**
     * Check if user has a right permission to create new schedule in this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function createSchedules(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',6)->exists());
    }

    /**
     * Check if user has a right permission to view all schedules in this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function viewAllSchedules(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && ($this->editDeleteAllSchedules($user,$hub)
                    || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',7)->exists()));
    }

    /**
     * Check if user has a right permission to edit/delete all schedules in this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function editDeleteAllSchedules(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',8)->exists());
    }

    /**
     * Check if user has a right permission to create new automation in this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function createAutomations(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',9)->exists());
    }

    /**
     * Check if user has a right permission to view all automations in this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function viewAllAutomations(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && ($this->editDeleteAllAutomations($user,$hub)
                    || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',10)->exists()));
    }

    /**
     * Check if user has a right permission to edit/delete all automations this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function editDeleteAllAutomations(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',11)->exists());
    }

    /**
     * Check if user has a right permission to add new member to this hub
     * @param User $user
     * @param Hub  $hub
     */
    public function addMembers(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',12)->exists());
    }

    /**
     * Check if user has a right permission to view all member of this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function viewAllMembers(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && ($this->editDeleteAllMembers($user,$hub)
                    || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',13)->exists()));
    }

    /**
     * Check if user has a right permission to edit/delete all members of this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function editDeleteAllMembers(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',14)->exists());
    }

    /**
     * Check if user has a right permission to create new event in this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function createEvents(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',15)->exists());
    }

    /**
     * Check if user has a right permission to view all events in this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function viewAllEvents(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && ($this->editDeleteAllEvents($user,$hub)
                    || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',16)->exists()));
    }

    /**
     * Check if user has a right permission to edit/delete all events in this hub
     * @param  User   $user
     * @param  Hub    $hub
     * @return boolean
     */
    public function editDeleteAllEvents(User $user, Hub $hub) {
        return $this->doAnything($user,$hub)
            || ($user->member($hub->id)->isActivated()
                && HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',17)->exists());
    }
}
