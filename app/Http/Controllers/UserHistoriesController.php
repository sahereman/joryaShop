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
        $user = $request->user();
        $histories = UserHistory::where(['user_id' => $user->id])->with('product')->get();
        return view('user_histories.index', [
            'histories' => $histories,
        ]);
    }


    // POST 添加浏览历史 TODO ...
    public function store(Request $request)
    {
        // 队列追加浏览历史
        $user = $request->user();
        $userHistory = new UserHistory();
        $userHistory->user_id = $user->id;
        $userHistory->product_id = $request->input('product_id');
        $userHistory->user()->associate($user);
        $userHistory->save();
        return response()->json([]);
    }

    // DELETE 删除
    public function destroy(Request $request, UserHistory $userHistory)
    {
        $this->authorize('delete', $userHistory);
        $userHistory->user()->dissociate();
        $userHistory->delete();
        return response()->json([]);
    }

    // DELETE 清空
    public function flush(Request $request)
    {
        $user = $request->user();
        UserHistory::where(['user_id' => $user->id])->delete();
        return response()->json([]);
    }
}
