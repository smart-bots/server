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

            if (!empty($schedule->deactivate_after_datetime)) {

                $deactivate_after_datetime = Carbon::createFromFormat('j-n-Y H:i', $schedule->deactivate_after_datetime, $schedule->hub->timezone);

                if ($deactivate_after_datetime->timestamp <= $now->timestamp) {
                    $schedule->deactivate();
                    $this->info('Schedule #'.$schedule->id.' deactivated');
                }

            }

            if (!empty($schedule->activate_after)) {

                $activate_after = Carbon::createFromFormat('j-n-Y H:i', $schedule->activate_after, $schedule->hub->timezone);

                if ($activate_after->timestamp <= $now->timestamp) {
                    $schedule->reactivate();
                    $schedule->activate_after = '';
                    $schedule->save();
                    $this->info('Schedule #'.$schedule->id.' activated');
                }
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

                    if (in_array($now->timestamp,explode('|',$schedule->next_run_time))) {

                        $this->info('It\'s time, go for # '.$schedule->id);

                        Artisan::queue('smartschedule:run', [
                            'schedule_id' => $schedule->id,
                            '-t' => $now->timestamp
                        ]);
                    }
                }
            } else {
                $this->info('Schedule # is '.$schedule->id.' deactivated');
            }
        }

    }
}
