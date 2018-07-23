<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function root()
    {
        return view('pages.root');
    }

    public function error()
    {
        return view('pages.error', ['msg' => '操作失败']);
    }


    public function success()
    {
        return view('pages.success', ['msg' => '操作成功']);
    }

}
