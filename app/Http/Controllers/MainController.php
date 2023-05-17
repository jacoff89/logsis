<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TestModel;

class MainController extends BaseController
{
    public function main(Request $request)
    {
        //sleep(2);
        //dd(123);

        return view('welcome', [

        ]);
    }
}
