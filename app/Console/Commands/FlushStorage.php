<?php

namespace SmartBots\Console\Commands;

use Illuminate\Console\Command;

use Storage;

class FlushStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all storaged file on local';

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
        Storage::disk('pub')->delete(Storage::disk('pub')->allFiles());
        Storage::disk('pub')->put('.gitkeep','');
        $this->info('Flushed all storaged files');
    }
}
