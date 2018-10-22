<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserHistory;
use Illuminate\Http\Request;

class UserHistoriesController extends Controller
{
    // GET 列表
    public function index(Request $request)
    {
        return view('user_histories.index', [
            'histories' => $request->user()->histories()->with('product')->orderByDesc('created_at')->get()->groupBy(function ($item, $key) {
                return date('Y.m.d', strtotime($item['created_at']));
            }),
        ]);
    }

    // DELETE 删除
    public function destroy(Request $request, UserHistory $userHistory)
    {
        $this->authorize('delete', $userHistory);
        $userHistory->user()->dissociate();
        $userHistory->delete();
        return view('user_histories.index', [
            'histories' => $request->user()->histories()->with('product')->orderByDesc('created_at')->get()->groupBy(function ($item, $key) {
                return date('Y.m.d', strtotime($item['created_at']));
            }),
        ]);
    }

    // DELETE 清空
    public function flush(Request $request)
    {
        UserHistory::where(['user_id' => $request->user()->id])->delete();
        return view('user_histories.index', [
            'histories' => $request->user()->histories()->with('product')->orderByDesc('created_at')->get()->groupBy(function ($item, $key) {
                return date('Y.m.d', strtotime($item['created_at']));
            }),
        ]);
    }
}
