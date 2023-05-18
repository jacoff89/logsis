<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class MainController extends BaseController
{
    public function main()
    {
        return view('welcome', [

        ]);
    }
}
