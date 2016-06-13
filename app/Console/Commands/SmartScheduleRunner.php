<?php

namespace SmartBots\Console\Commands;

use Illuminate\Console\Command;

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

use Carbon;

use Artisan;

class SmartScheduleRunner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smartschedule:runner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The SmartSchedule Runner';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $hubs = Hub::all();

        $schedules = collect([]);

        foreach ($hubs as $hub) {
            $schedules = $schedules->merge($hub->schedules()->get());
        }

        foreach ($schedules as $schedule) {

            $now = Carbon::now()->setTimezone($schedule->hub->timezone);
            $now->second = 0;

            if (!empty($schedule->deactivate_after_datetime) && strtotime($schedule->deactivate_after_datetime) <= $now->timestamp) {
                $schedule->deactivate();
                $this->info('Schedule #'.$schedule->id.' deactivated');
            }

            if (!empty($schedule->activate_after_datetime) && strtotime($schedule->activate_after) <= $now->timestamp) {
                $schedule->reactivate();
                $schedule->activate_after = null;
                $schedule->save();
                $this->info('Schedule #'.$schedule->id.' activated');
            }

            if ($schedule->isActivated()) {

                $this->info('Schedule # is '.$schedule->id.' activated');

                if ($schedule->type == 1) { // If one-time

                    $data = Carbon::createFromFormat('j-n-Y H:i', $schedule->data, $schedule->hub->timezone);

                    $this->info($data->timestamp.' '.$now->timestamp);

                    if ($data->timestamp == $now->timestamp) {

                        $this->info('It\'s time, go for # '.$schedule->id);

                        Artisan::queue('smartschedule:run', [
                            'schedule_id' => $schedule->id
                        ]);
                    }

                } else if ($schedule->type == 2) { // If repeat

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

                        // $global_schedule->command('runschedule '.$schedule->id)->cron(implode(' ', $cron));
                    }
                }
            } else {
                $this->info('Schedule # is '.$schedule->id.' deactivated');
            }
        }

    }
}
