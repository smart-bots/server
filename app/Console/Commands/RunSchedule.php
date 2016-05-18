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

class RunSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'runschedule {schedule_id : The Id of schedule}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a schedule';

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
        $schedule_id = $this->argument('schedule_id');
        $schedule = Schedule::findOrFail($schedule_id);
        if ($schedule->isActivated()) {
            if ($schedule->condition_type != 0) {
                $schedule_conditions = explode('|',$schedule->condition_data);
                for($i=0; $i<count($schedule_conditions); $i++) {
                    $schedule_conditions[$i] = explode(',',$schedule_conditions[$i]);
                    $return_condition[] = Bot::findOrFail($schedule_conditions[$i][0])->realStatus() == $schedule_conditions[$i][1];
                }
                foreach ($return_condition as $single_return_condition) {
                    if ($schedule->condition_method == 1 && $single_return_condition == false) {
                        $return_condition = false;
                        break;
                    }
                    if ($schedule->condition_method == 2 && $single_return_condition == true) {
                        $return_condition = true;
                        break;
                    }
                }
                if (($return_condition == true && $schedule->condition_type == 1)
                || ($return_condition == false && $schedule->condition_type == 2)) {
                    $run_schedule = true;
                } else {
                    $run_schedule = false;
                }
            } else {
                $run_schedule = true;
            }
            if ($run_schedule == true) {
                $schedule_actions = explode('|',$schedule->action);
                for($i=0; $i<count($schedule_actions); $i++) {
                    $schedule_actions[$i] = explode(',',$schedule_actions[$i]);
                    switch ($schedule_actions[$i][0]) {
                        case 1:
                            Bot::findOrFail($schedule_actions[$i][1])->toggle();
                            break;
                        case 2:
                            Bot::findOrFail($schedule_actions[$i][1])->control(1);
                            break;
                        case 3:
                            Bot::findOrFail($schedule_actions[$i][1])->control(0);
                            break;
                    }
                }
                if ($schedule->type == 1) {
                    $schedule->delete();
                }
                $schedule->ran_times++;
                if ($schedule->ran_times >= $schedule->deactivate_after_times) {
                    $schedule->status = 0;
                }
                $schedule->save();
            }
        }
    }
}
