<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetLongIpsRequest;
use App\Http\Requests\GetLongMethodsRequest;
use App\Models\ExecutionTimeLog;
use Illuminate\Routing\Controller as BaseController;

class GetDataController extends BaseController
{
    private $executionTimeLog;

    public function __construct()
    {
        $this->executionTimeLog = new ExecutionTimeLog();
    }

    public function getMethods(GetLongMethodsRequest $request)
    {
        $res = $request->validated();
        $data = $this->executionTimeLog->getLongMethods($res['startDate'], $res['endDate']);
        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function getIps(GetLongIpsRequest $request)
    {
        $res = $request->validated();
        $data = $this->executionTimeLog->getLongIps($res['startDate'], $res['endDate'], $res['isInTheList']);
        return response()->json(['status' => 'success', 'data' => $data]);
    }
}
