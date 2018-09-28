<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function root(Request $request)
    {
        // TODO ... 首页
        return view('index.root');
    }
}
