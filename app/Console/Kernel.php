<?php

namespace SmartBots\Console;

use Illuminate\Console\Scheduling\Schedule as GlobalSchedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Carbon\Carbon;

use SmartBots\{
    User,
    Hub,
    Bot,
    Member,
    Schedule,
    Automation,
    HubPermission,
    BotPermission,
    SchedulePermission,
    AutomationPermission
};


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\RunSchedule::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(GlobalSchedule $global_schedule)
    {
        $global_schedule->command('inspire')->at('23:04');

        $hubs = Hub::all();
        $schedules = collect([]);
        foreach ($hubs as $hub) {
            $schedules = $schedules->merge($hub->schedules()->get());
        }
        foreach ($schedules as $schedule) {
            if (strtotime($schedule->deactivate_after_datetime) <= Carbon::now()->timestamp) {
                $schedule->deactivate();
            }
            if (strtotime($schedule->activate_after) <= Carbon::now()->timestamp) {
                $schedule->reactivate();
            }
            if ($schedule->isActivated()) {
                if ($schedule->type == 1) {
                    $data = Carbon::createFromTimestamp(strtotime($schedule->data));
                    $cron[0] = $data->minute;
                    $cron[1] = $data->hour;
                    $cron[2] = $data->day;
                    $cron[3] = $data->month;
                    $cron[4] = '*';
                    $cron[5] = $data->year;
                    // dd(implode(' ', $cron));
                    $global_schedule->command('runschedule '.$schedule->id)->cron(implode(' ', $cron));
                } else if ($schedule->type == 2) {
                    $data = explode('|',$schedule->data);
                    foreach ($data as $single_data) {
                        $single_data = explode(',',$single_data);
                        switch ($single_data[0]) {
                            case 1:
                                $cron[0] = '*/'.$single_data[1]; // minute
                                $cron[1] = '*'; // hour
                                $cron[2] = '*'; // day
                                $cron[3] = '*'; // month
                                $cron[4] = '*'; // day of week
                                $cron[5] = '*'; // year
                                break;
                            case 2:
                                $cron[0] = $single_data[2];
                                $cron[1] = '*/'.$single_data[1];
                                $cron[2] = '*';
                                $cron[3] = '*';
                                $cron[4] = '*';
                                $cron[5] = '*';
                                break;
                            case 3:
                                explode(':',$single_data[2]);
                                $cron[0] = $single_data[2][1];
                                $cron[1] = $single_data[2][2];
                                $cron[2] = '*/'.$single_data[1];
                                $cron[3] = '*';
                                $cron[4] = '*';
                                $cron[5] = '*';
                                break;
                            case 4:
                                $pattern = '/^(Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Saturday) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/';
                                preg_match($pattern, $single_data[2], $matches);
                                switch ($matches[1]) {
                                    case 'Sunday':
                                        $cron[4] = 0;
                                        break;
                                    case 'Monday':
                                        $cron[4] = 1;
                                        break;
                                    case 'Tuesday':
                                        $cron[4] = 2;
                                        break;
                                    case 'Wednesday':
                                        $cron[4] = 3;
                                        break;
                                    case 'Thurday':
                                        $cron[4] = 4;
                                        break;
                                    case 'Friday':
                                        $cron[4] = 5;
                                        break;
                                    case 'Saturday':
                                        $cron[4] = 6;
                                        break;
                                }
                                $cron[0] = $matches[3];
                                $cron[1] = $matches[2];
                                $cron[2] = '*';
                                $cron[3] = '*';
                                $cron[5] = '*';
                                break;
                            case 5:
                                $pattern = '/^([012]{1}[0-9]{1}|[3]{1}[01]) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/';
                                preg_match($pattern, $single_data[2], $matches);
                                $cron[0] = $matches[3];
                                $cron[1] = $matches[2];
                                $cron[2] = $matches[1];
                                $cron[3] = '*/'.$single_data[1];
                                $cron[4] = '*';
                                $cron[5] = '*';
                                break;
                            case 6:
                                $pattern = '/^(January|February|March|April|May|June|July|August|September|October|November|December) ([012]{1}[0-9]{1}|[3]{1}[01]) ([01]{1}[0-9]{1}|[2]{1}[0-3]{1}):([012345]{1}[0-9]{1})$/';
                                preg_match($pattern, $single_data[2], $matches);
                                $cron[0] = $matches[4];
                                $cron[1] = $matches[3];
                                $cron[2] = $matches[2];
                                switch ($matches[1]) {
                                    case 'January':
                                        $cron[3] = 1;
                                        break;
                                    case 'February':
                                        $cron[3] = 2;
                                        break;
                                    case 'March':
                                        $cron[3] = 3;
                                        break;
                                    case 'April':
                                        $cron[3] = 4;
                                        break;
                                    case 'May':
                                        $cron[3] = 5;
                                        break;
                                    case 'June':
                                        $cron[3] = 6;
                                        break;
                                    case 'July':
                                        $cron[3] = 7;
                                        break;
                                    case 'August':
                                        $cron[3] = 8;
                                        break;
                                    case 'September':
                                        $cron[3] = 9;
                                        break;
                                    case 'October':
                                        $cron[3] = 10;
                                        break;
                                    case 'November':
                                        $cron[3] = 11;
                                        break;
                                    case 'December':
                                        $cron[3] = 12;
                                        break;
                                }
                                $cron[4] = '*';
                                $cron[5] = '*/'.$single_data[1];
                                break;
                        }
                        // dd(implode(' ', $cron));
                        $global_schedule->command('runschedule '.$schedule->id)->cron(implode(' ', $cron));
                    }
                }
            }
        }
    }


}
