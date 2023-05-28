<?php

namespace Database\Seeders;

use App\Models\ListOfIps;
use Illuminate\Database\Seeder;

class ListOfIpsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = ['black', 'white'];

        for ($i = 1; $i <= 180; $i++) {
            $ip = new ListOfIps();
            $ip->ip = '192.168.1.' . $i;
            $ip->list = $list[rand(0,1)];
            $ip->save();
        }
    }
}
