<?php

namespace App\Http\Middleware;

use App\Services\WorkingWithRabbitMQ;
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
     * @param Illuminate\Http\Request $request
     * @return void
     */
    public function terminate($request)
    {
        if (defined('LARAVEL_START') and $request instanceof Request) {

            $today = date("Y-m-d H:i:s");
            $controller = app('request')->route()->getAction()['controller'];

            $controller_arr = explode('@', $controller);
            $controller_name = $controller_arr[0];
            $method_name = $controller_arr[1];

            $ip_address = $request->ip();

            $execution_time = microtime(true) - LARAVEL_START;

            $send_array = [
                'create_date' => $today,
                'method_name' => $method_name,
                'controller_name' => $controller_name,
                'execution_time' => $execution_time,
                'ip_address' => $ip_address
            ];

            $send_message = json_encode($send_array);

            $rabbit_MQ = new WorkingWithRabbitMQ(env('RABBIT_MQ_QUEUE_NAME'));
            $rabbit_MQ->send($send_message);

        }
    }
}
