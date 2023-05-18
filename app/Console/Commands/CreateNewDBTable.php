<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RabbitReceiveStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-table:execution_time_log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание таблицы execution_time_log в БД';

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
     * @return int
     */
    public function handle()
    {
        DB::statement('DROP TABLE IF EXISTS `execution_time_log`');
        DB::unprepared('CREATE TABLE `execution_time_log` (
            `controller_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
            `method_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
            `execution_time` float NOT NULL,
            `date` datetime NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

}
