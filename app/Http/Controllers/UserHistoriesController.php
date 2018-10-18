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
        UserHistory::where(['user_id' => $request->user()->id])->delete();
        return response()->json([]);
    }
}
