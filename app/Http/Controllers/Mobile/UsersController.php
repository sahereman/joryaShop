<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    // GET 主页
    public function home(Request $request)
    {
        $user = Auth::user();
        $orders = $user->orders()
            ->with('items.sku.product')
            ->where('status', '<>', Order::ORDER_STATUS_CLOSED)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();
        $guesses = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('heat')->limit(8)->get();
        return view('mobile.users.home', [
            'user' => $user,
            'orders' => $orders,
            'guesses' => $guesses,
        ]);
    }

    // GET 编辑个人信息页面
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('mobile.users.edit', [
            'user' => $user,
        ]);
    }

    // GET 修改密码页面
    public function password(User $user)
    {
        $this->authorize('update', $user);

        return view('mobile.users.password');
    }

    // GET 设置 页面
    public function settingShow()
    {
        return view('mobile.users.setting');
    }

    // PUT 修改用户密码
    public function updatePassword(UpdatePasswordRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update([
            'password' => bcrypt($request->input('password')),
        ]);

        return redirect()->route('mobile.reset.success.show');
    }

}
