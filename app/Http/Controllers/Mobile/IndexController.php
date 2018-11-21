<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function root()
    {

        return view('mobile.index.root', [

        ]);
    }

    public function localeShow()
    {
        return view('mobile.index.locale', [

        ]);
    }
}
