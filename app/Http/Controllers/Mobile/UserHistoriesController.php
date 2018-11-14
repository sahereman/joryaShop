<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\UserHistory;
use Illuminate\Http\Request;

class UserHistoriesController extends Controller
{
    // GET 列表
    public function index(Request $request)
    {
        $this->validate($request, [
            'page' => 'sometimes|required|integer|min:1',
        ], [], [
            'page' => '页码',
        ]);
        $current_page = $request->has('page') ? $request->input('page') : 1;
        $histories = $request->user()->histories()->with('product')->orderByDesc('created_at')->get()->groupBy(function ($item, $key) {
            return date('Y.m.d', strtotime($item['created_at']));
        });
        $history_count = $histories->count();
        $page_count = ceil($history_count / 5);
        $previous_page = ($current_page > 1) ? ($current_page - 1) : false;
        $next_page = ($current_page < $page_count) ? ($current_page + 1) : false;
        return view('user_histories.index', [
            'histories' => $histories->forpage($current_page, 5),
            'previous_page' => $previous_page,
            'next_page' => $next_page,
        ]);
    }

}
