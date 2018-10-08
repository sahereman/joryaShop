<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserHistory;
use Illuminate\Http\Request;

class UserHistoriesController extends Controller
{
    // GET 列表
    public function index (Request $request)
    {
        return view('user_histories.index', []);
    }


    // POST 添加浏览历史 TODO ...
    public function store (Request $request)
    {
        // TODO ...
        // 队列追加浏览历史
    }

    // DELETE 删除
    public function destroy (UserHistory $userHistory)
    {
        // TODO ...
    }

    // DELETE 清空
    public function flush (Request $request)
    {
        // TODO ...
    }
}
