<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /*public function root()
    {
        return view('pages.root');
    }*/

    public function error()
    {
        return view('pages.error', ['msg' => '操作失败']);
    }


    public function success()
    {
        return view('pages.success', ['msg' => '操作成功']);
    }

    public function index (Request $request)
    {
        return view('pages.index');
    }

    public function show (Page $page)
    {
        return view('pages.show', []);
    }

    public function create ()
    {
        return view('pages.create_and_edit');
    }

    public function store (Request $request)
    {
        // TODO ...
    }

    public function edit (Page $page)
    {
        return view('pages.create_and_edit', []);
    }

    public function update (Page $page)
    {
        // TODO ...
    }

    public function destroy (Page $page)
    {
        // TODO ...
    }
}
