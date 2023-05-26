<?php

namespace Database\Seeders;

use App\Models\IpBlackList;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IpBlackListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 80; $i++) {
            $ip = new IpBlackList();
            $ip->ip = '192.168.1.' . $i;
            $ip->save();
        }
    }
}
