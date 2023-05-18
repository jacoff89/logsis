<?php

namespace App\Http\Middleware;

use App\Models\RabbitMQ;
use Closure;
use Illuminate\Http\Request;

class MeasureResponseTime
{

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     *
     * @param  Illuminate\Http\Request $request
     * @return void
     */
    public function terminate($request)
    {
        if (defined('LARAVEL_START') and $request instanceof Request) {

            $today = date("Y-m-d H:i:s");
            $controller = app('request')->route()->getAction()['controller'];

            $controllerArr = explode('@', $controller);
            $controllerName = $controllerArr[0];
            $methodName = $controllerArr[1];

            $executionTime = round(microtime(true) - LARAVEL_START, 2);

            $sendArray = [
                'date' => $today,
                'methodName' => $methodName,
                'controllerName' => $controllerName,
                'executionTime' => $executionTime
            ];

            $sendMessage = json_encode($sendArray);

            $rabbitMQ = new RabbitMQ();
            $rabbitMQ->send($sendMessage);

        }
    }
}
