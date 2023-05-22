<?php

namespace App\Http\Controllers;

use App\Models\ExecutionTimeLog;
use Illuminate\Routing\Controller as BaseController;

class MainController extends BaseController
{
    public function main()
    {

        //$logs = ExecutionTimeLog::first();
        //dd($logs->execution_time);
        return view('welcome', [

        ]);
    }
}
