<?php

namespace SmartBots\Console\Commands;

use Illuminate\Console\Command;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a database with infomation configured in .env';

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
        // $servername = env('DB_HOST');
        // $username = env('DB_USERNAME');
        // $password = env('DB_PASSWORD');
        // $database = env('DB_DATABASE');
        // $collation = 'utf8_unicode_ci';
        // $charset = 'utf8';

        $servername = config('database.connections.mysql.host');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');
        $collation = config('database.connections.mysql.collation');
        $charset = config('database.connections.mysql.charset');

        $conn = mysqli_connect($servername, $username, $password);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "CREATE DATABASE `$database` CHARACTER SET `$charset` COLLATE `$collation`";

        if (mysqli_query($conn, $sql)) {
            $this->line('Database created successfully');
        } else {
            $this->line('Error creating database: '.mysqli_error($conn));
        }

        mysqli_close($conn);
    }
}
