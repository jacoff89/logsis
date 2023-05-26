<?php

namespace Database\Seeders;

use App\Models\IpWhiteList;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IpWhiteListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 81; $i <= 160; $i++) {
            $ip = new IpWhiteList();
            $ip->ip = '192.168.1.' . $i;
            $ip->save();
        }
    }
}
