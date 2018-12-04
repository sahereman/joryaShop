<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UserRequest;
use App\Models\CountryCode;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /*public function show (User $user)
    {
        if(Auth::check()){
            return view('users.show', []);
        }else{
            return redirect()->route('login');
        }
    }*/

    // GET 主页
    public function home(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $orders = $user->orders()
                ->with('items.sku.product')
                ->where('status', '<>', Order::ORDER_STATUS_CLOSED)
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
            $guesses = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('heat')->limit(8)->get();
            return view('users.home', [
                'user' => $user,
                'orders' => $orders,
                'guesses' => $guesses,
            ]);
        } else {
            return redirect()->back();
        }
    }

    // GET 编辑个人信息页面
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', [
            'user' => $user,
        ]);
    }

    // GET 修改密码页面
    public function password(User $user)
    {
        $this->authorize('update', $user);

        return view('users.password', [
            'user' => $user,
        ]);
    }

    // GET 修改密码成功页面
    public function passwordSuccess(User $user)
    {
        $this->authorize('update', $user);

        return view('users.password_success', [
            'user' => $user,
        ]);
    }

    // PUT 修改密码
    public function updatePassword(UpdatePasswordRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update([
            'password' => bcrypt($request->input('password')),
        ]);

        return redirect()->route('users.password_success', [
            'user' => $user->id,
        ]);
    }

    // PUT 编辑个人信息提交 & 修改密码提交 & 修改手机提交 & 绑定手机提交
    public function update(UserRequest $request, User $user, ImageUploadHandler $imageUploadHandler)
    {

        $this->authorize('update', $user);

        $data = $request->only('avatar', 'email', 'password', 'real_name', 'gender', 'qq', 'wechat', 'country_code', 'phone', 'facebook');
        // $data = $request->only('name', 'avatar', 'email', 'password', 'real_name', 'gender', 'qq', 'wechat', 'country_code', 'phone', 'facebook');

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $imageUploadHandler->uploadOriginal($request->avatar);
        }

        if ($request->has('password') && $user->password != $data['password']) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);

        return redirect()->back();
    }

    // POST logout
    public function logout()
    {
        Auth::logout();
    }
}
