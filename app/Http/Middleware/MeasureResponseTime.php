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
            $controllerName = app('request')->route()->getAction()['controller'];
            $executionTime = microtime(true) - LARAVEL_START;
            $executionTimeRound = round($executionTime, 2);

            //app('log')->debug($res);

            $sendArray = ['date' => $today, 'controllerName' => $controllerName, 'executionTime' => $executionTimeRound];
            $sendMessage = json_encode($sendArray);

            $rabbitMQ = new RabbitMQ();
            $rabbitMQ->send($sendMessage);

        }
    }
}
