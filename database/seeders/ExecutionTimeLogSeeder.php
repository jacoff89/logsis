<?php

namespace Database\Seeders;

use App\Models\ExecutionTimeLog;
use Illuminate\Database\Seeder;

class ExecutionTimeLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 10000; $i++) {
            $log = new ExecutionTimeLog();
            $log->controller_name = 'App\Http\Controllers\Controller' . rand(1, 20);
            $log->method_name = 'method' . rand(1, 50);
            $log->execution_time = rand(1, 1000)/100;
            $log->create_date = date("Y-m-d H:i:s");
            $log->ip_address = '192.168.1.' . rand(1, 255);
            $log->save();
        }
    }
}
