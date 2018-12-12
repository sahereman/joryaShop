<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class GetWechatOpenId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (is_wechat_browser() && !Session::has('wechat-basic_user_info')) {
            Session::put('previous_url', $request->url());
            return redirect(route('mobile.payments.get_wechat_open_id'));
        }
        return $next($request);
    }
}
