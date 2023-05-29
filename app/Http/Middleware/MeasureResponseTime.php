<?php

namespace App\Http\Middleware;

use App\Services\WorkingWithRabbitMQ;
use Closure;

class MeasureResponseTime
{

    public function handle($request, Closure $next)
    {
        $startTime = microtime(true);

        $response = $next($request);

        $controller = app('request')->route()->getAction()['controller'];
        $controllerArr = explode('@', $controller);
        $controllerName = $controllerArr[0];
        $methodName = $controllerArr[1];
        $ipAddress = $request->ip();
        $executionTime = microtime(true) - $startTime;

        $send_array = [
            'createDate' => date("Y-m-d H:i:s"),
            'methodName' => $methodName,
            'controllerName' => $controllerName,
            'executionTime' => $executionTime,
            'ipAddress' => $ipAddress
        ];

        $send_message = json_encode($send_array);

        $rabbit_MQ = new WorkingWithRabbitMQ(config('logsis.rabbit_mq_queue_name'));
        $rabbit_MQ->send($send_message);

        return $response;
    }

}
