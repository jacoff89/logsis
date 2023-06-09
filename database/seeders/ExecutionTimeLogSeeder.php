<?php

namespace Database\Seeders;

use App\Models\ExecutionTimeLog;
use Carbon\Carbon;
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
        for ($i = 1; $i <= 1000; $i++) {
            $log = new ExecutionTimeLog();
            $log->controller_name = 'App\Http\Controllers\Controller' . rand(1, 20);
            $log->method_name = 'method' . rand(1, 50);
            $log->execution_time = rand(0.1, 10000)/1000;
            $log->create_date = Carbon::now()->toDateTimeString();
            $log->ip_address = '192.168.1.' . rand(1, 255);
            $log->save();
        }
    }
}
