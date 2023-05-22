<?php

namespace App\Http\Controllers;

use App\Models\ExecutionTimeLog;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class MainController extends BaseController
{
    public function main()
    {
        $res = ExecutionTimeLog::select('controller_name', 'method_name')
            ->selectRaw('MAX(execution_time) as execution_time')
            ->groupBy('controller_name', 'method_name')
            ->orderByDesc('execution_time')
            ->limit(50)
            ->get();

        $res = ExecutionTimeLog::leftJoin('ip_black_lists', function($join) {
            $join->on('execution_time_logs.ip_address', '=', 'ip_black_lists.ip');
        })
            ->leftJoin('ip_white_lists', 'execution_time_logs.ip_address', '=', 'ip_white_lists.ip')
            ->select('ip_address')
            ->selectRaw('MAX(execution_time) as execution_time')
            ->selectRaw('(ip_black_lists.ip IS NOT NULL) AS is_black_lists')
            ->selectRaw('(ip_white_lists.ip IS NOT NULL) AS is_white_lists')
            ->whereNull('ip_black_lists.ip')
            ->whereNull('ip_white_lists.ip')
            ->groupBy('ip_address')
            ->orderByDesc('execution_time')
            ->limit(50)
            ->get();

        dd($res);

        return view('welcome', [

        ]);
    }
}
