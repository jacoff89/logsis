<?php

namespace App\Http\Middleware;

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
            //$res = app('request')->route()->getAction()['controller'];
            $res = microtime(true) - LARAVEL_START;
            app('log')->debug($res);
        }
    }
}
