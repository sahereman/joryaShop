<?php

namespace App\Http\Controllers\Mobile;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Models\UserHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class UserHistoriesController extends Controller
{
    // GET 列表
    public function index(Request $request)
    {
        // refresh user browsing history ...
        $user = $request->user();
        $userHistoriesController = new \App\Http\Controllers\UserHistoriesController();
        $userHistoriesController->refreshUserBrowsingHistoryCacheByUser($user);

        return view('mobile.user_histories.index');
    }

    public function more(Request $request)
    {
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
            // return Carbon::createFromFormat('Y-m-d H:i:s', $item['browsed_at'])->toDateString();
        });
        $history_count = $histories->count();
        $page_count = ceil($history_count / 5);
        $next_page = ($current_page < $page_count) ? ($current_page + 1) : false;
        $histories_for_page = $histories->forPage($current_page, 5)->toArray();
        if ($current_page == 1) {
            $first_history = array_shift_assoc($histories_for_page);
            foreach ($first_history as $key => $value) {
                $histories_for_page = array_unshift_assoc($histories_for_page, Carbon::createFromFormat('Y.m.d', $key)->diffForHumans(), $value);
            }
        }
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'histories' => $histories_for_page,
                'request_url' => $next_page ? route('mobile.user_histories.more') . '?page=' . $next_page : false,
            ],
        ]);
    }
}
