<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\User;
use App\Models\UserHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class UserHistoriesController extends Controller
{
    // GET 列表
    public function index(Request $request)
    {
        // refresh user browsing history ...
        $user = $request->user();
        $this->refreshUserBrowsingHistoryCacheByUser($user);

        $current_page = $request->has('page') ? $request->input('page') : 1;
        if (preg_match('/^\d+$/', $current_page) != 1) {
            if (App::isLocale('en')) {
                throw new InvalidRequestException('The parameter page must be an integer.');
            } else {
                throw new InvalidRequestException('页码参数必须为数字！');
            }
        }
        $histories = $request->user()->histories()->with('product')->orderByDesc('browsed_at')->get()->groupBy(function ($item, $key) {
            return date('Y.m.d', strtotime($item['browsed_at']));
        });
        $history_count = $histories->count();
        $page_count = ceil($history_count / 5);
        $previous_page = ($current_page > 1) ? ($current_page - 1) : false;
        $next_page = ($current_page < $page_count) ? ($current_page + 1) : false;
        return view('user_histories.index', [
            'histories' => $histories->forPage($current_page, 5),
            'previous_page' => $previous_page,
            'next_page' => $next_page,
        ]);
    }

    // DELETE 删除
    public function destroy(Request $request, UserHistory $history)
    {
        $this->authorize('delete', $history);
        $history->user()->dissociate();
        $result = $history->delete();
        if ($result) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);
        } else {
            return response()->json([
                'code' => 422,
                'message' => 'Unprocessable Entity',
            ], 422);
        }
    }

    // DELETE 清空
    public function flush(Request $request)
    {
        $result = UserHistory::where(['user_id' => $request->user()->id])->delete();

        // refresh cache ...
        $user = $request->user();
        if (Cache::has($user->id . '-user_browsing_history_count') && Cache::has($user->id . '-user_browsing_history_list')) {
            Cache::forever($user->id . '-user_browsing_history_count', 0);
            Cache::forever($user->id . '-user_browsing_history_list', []);
        }

        if ($result) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);
        } else {
            return response()->json([
                'code' => 422,
                'message' => 'Unprocessable Entity',
            ], 422);
        }
    }

    // refresh user browsing history ...
    public function refreshUserBrowsingHistoryCacheByUser(User $user)
    {
        if (Cache::has($user->id . '-user_browsing_history_count') && Cache::has($user->id . '-user_browsing_history_list') && Cache::has($user->id . '-user_browsing_history_list_stored')) {
            if (Cache::get($user->id . '-user_browsing_history_count') > 0 && Cache::get($user->id . '-user_browsing_history_list') !== []) {
                $user_browsing_history_list = Cache::get($user->id . '-user_browsing_history_list');
                $user_browsing_history_list_stored = Cache::get($user->id . '-user_browsing_history_list_stored');
                foreach ($user_browsing_history_list as $browsed_at => $product_ids) {
                    foreach ($product_ids as $product_id) {
                        $user_browsing_history['user_id'] = $user->id;
                        $user_browsing_history['product_id'] = $product_id;
                        $user_browsing_history['browsed_at'] = $browsed_at;
                        UserHistory::create($user_browsing_history);
                    }
                    $user_browsing_history_list_stored[$browsed_at] = array_merge($user_browsing_history_list_stored[$browsed_at], $user_browsing_history_list[$browsed_at]);
                }
                // refresh cache ...
                Cache::forever($user->id . '-user_browsing_history_count', 0);
                Cache::forever($user->id . '-user_browsing_history_list', [
                    today()->toDateString() => [],
                ]);
                Cache::forever($user->id . '-user_browsing_history_list_stored', $user_browsing_history_list_stored);
            }
        }
    }
}
