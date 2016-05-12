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
    public function doAnything(User $user, Hub $hub) { // is Root admin?
        return HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->whereIn('data',[0,12])->exists();
    }
    public function view(User $user, Hub $hub) {
        return $this->doAnything($user,$hub) || $this->editDelete($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',1)->exists();
    }
    public function editDelete(User $user) {
        return $this->doAnything($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',2)->exists();
    }
    public function addBots(User $user) {
        return $this->doAnything($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',3)->exists();
    }
    public function viewControlAllBots(User $user) {
        return $this->doAnything($user,$hub) || $this->editDeleteAllBots($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',4)->exists();
    }
    public function editDeleteAllBots(User $user) {
        return $this->doAnything($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',5)->exists();
    }
    public function createSchedules(User $user) {
        return $this->doAnything($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',6)->exists();
    }
    public function viewAllSchedules(User $user) {
        return $this->doAnything($user,$hub) || $this->editDeleteAllSchedules($user,$hub) ||HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',7)->exists();
    }
    public function editDeleteAllSchedules(User $user) {
        return $this->doAnything($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',8)->exists();
    }
    public function createAutomations(User $user) {
        return $this->doAnything($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',9)->exists();
    }
    public function viewAllAutomations(User $user) {
        return $this->doAnything($user,$hub) || $this->editDeleteAllAutomations($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',10)->exists();
    }
    public function editDeleteAllAutomations(User $user) {
        return $this->doAnything($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',11)->exists();
    }
    public function addMembers(User $user, Hub $hub) {
        return $this->doAnything($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',12)->exists();
    }
    public function viewMembers(User $user, Hub $hub) {
        return $this->doAnything($user,$hub) || $this->editDeleteMembers($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',13)->exists();
    }
    public function editDeleteMembers(User $user, Hub $hub) {
        return $this->doAnything($user,$hub) || HubPermission::where('user_id',$user->id)->where('hub_id',$hub->id)->where('data',14)->exists();
    }
}
