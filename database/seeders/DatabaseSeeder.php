<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 80; $i++) {
            DB::table('ip_black_lists')->insert(['ip' => '192.168.1.' . $i]);
        }

        for ($i = 81; $i <= 160; $i++) {
            DB::table('ip_white_lists')->insert(['ip' => '192.168.1.' . $i]);
        }

        for ($i = 1; $i <= 10000; $i++) {
            DB::table('execution_time_logs')->insert([
                'controller_name' => 'App\Http\Controllers\Controller' . rand(1, 20),
                'method_name' => 'method' . rand(1, 50),
                'execution_time' => rand(1, 1000)/100,
                'create_date' => date("Y-m-d H:i:s"),
                'ip_address' => '192.168.1.' . rand(1, 255)
            ]);
        }

    }
}
