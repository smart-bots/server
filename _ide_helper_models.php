<?php
/**
 * An helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace SmartBots{
/**
 * SmartBots\Automation
 *
 * @property-read \SmartBots\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\AutomationPermission[] $automationpermissions
 * @property-read \SmartBots\Hub $hub
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Automation activated()
 */
	class Automation extends \Eloquent {}
}

namespace SmartBots{
/**
 * SmartBots\AutomationPermission
 *
 */
	class AutomationPermission extends \Eloquent {}
}

namespace SmartBots{
/**
 * SmartBots\Bot
 *
 * @property integer $id
 * @property integer $hub_id
 * @property string $name
 * @property string $token
 * @property string $image
 * @property string $description
 * @property boolean $type
 * @property boolean $status
 * @property boolean $true
 * @property-read \SmartBots\Hub $hub
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\BotPermission[] $botpermissions
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Bot whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Bot whereHubId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Bot whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Bot whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Bot whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Bot whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Bot whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Bot whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Bot whereTrue($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Bot activated()
 */
	class Bot extends \Eloquent {}
}

namespace SmartBots{
/**
 * SmartBots\BotPermission
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $bot_id
 * @property boolean $high
 * @property-read \SmartBots\Bot $bot
 * @property-read \SmartBots\User $user
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\BotPermission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\BotPermission whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\BotPermission whereBotId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\BotPermission whereHigh($value)
 */
	class BotPermission extends \Eloquent {}
}

namespace SmartBots{
/**
 * SmartBots\Hub
 *
 * @property integer $id
 * @property string $token
 * @property string $name
 * @property string $image
 * @property string $description
 * @property string $timezone
 * @property boolean $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\Bot[] $bots
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\Member[] $members
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\HubPermission[] $hubpermissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\Schedule[] $schedules
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\User[] $users
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Hub whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Hub whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Hub whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Hub whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Hub whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Hub whereTimezone($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Hub whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Hub activated()
 */
	class Hub extends \Eloquent {}
}

namespace SmartBots{
/**
 * SmartBots\HubPermission
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $hub_id
 * @property integer $data
 * @property-read \SmartBots\User $user
 * @property-read \SmartBots\Hub $hub
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\HubPermission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\HubPermission whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\HubPermission whereHubId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\HubPermission whereData($value)
 */
	class HubPermission extends \Eloquent {}
}

namespace SmartBots{
/**
 * SmartBots\Member
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $hub_id
 * @property boolean $status
 * @property-read \SmartBots\Hub $hub
 * @property-read \SmartBots\User $user
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Member whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Member whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Member whereHubId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Member whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Member activated()
 */
	class Member extends \Eloquent {}
}

namespace SmartBots{
/**
 * SmartBots\Schedule
 *
 * @property integer $id
 * @property integer $hub_id
 * @property string $name
 * @property string $description
 * @property string $action
 * @property boolean $type
 * @property string $data
 * @property boolean $condition_type
 * @property boolean $condition_method
 * @property string $condition_data
 * @property string $activate_after
 * @property string $deactivate_after_times
 * @property string $deactivate_after_datetime
 * @property string $next_run_time
 * @property boolean $status
 * @property integer $ran_times
 * @property-read \SmartBots\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\SchedulePermission[] $schedulepermissions
 * @property-read \SmartBots\Hub $hub
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereHubId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereAction($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereData($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereConditionType($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereConditionMethod($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereConditionData($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereActivateAfter($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereDeactivateAfterTimes($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereDeactivateAfterDatetime($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereNextRunTime($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule whereRanTimes($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\Schedule activated()
 */
	class Schedule extends \Eloquent {}
}

namespace SmartBots{
/**
 * SmartBots\SchedulePermission
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $schedule_id
 * @property boolean $high
 * @property-read \SmartBots\Schedule $schedule
 * @property-read \SmartBots\User $user
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\SchedulePermission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\SchedulePermission whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\SchedulePermission whereScheduleId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\SchedulePermission whereHigh($value)
 */
	class SchedulePermission extends \Eloquent {}
}

namespace SmartBots{
/**
 * SmartBots\User
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property string $avatar
 * @property string $remember_token
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\Member[] $members
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\BotPermission[] $botpermissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\SchedulePermission[] $schedulepermissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\HubPermission[] $hubpermissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\SmartBots\Hub[] $hubs
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\User wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\SmartBots\User whereRememberToken($value)
 */
	class User extends \Eloquent {}
}

