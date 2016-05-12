<?php

namespace SmartBots\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

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
}
