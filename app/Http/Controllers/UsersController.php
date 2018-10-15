<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                //->where('status', '<>', 'closed')
                //->whereNotIn('status', ['closed'])
                //->whereIn('status', ['paying', 'shipping', 'receiving', 'refunding', 'completed'])
                //->where('status', 'in', ['paying', 'shipping', 'receiving', 'refunding', 'completed'])
                //->orderByDesc('updated_at')
                ->where('status', 'paying')
                ->orderByDesc('created_at')
                ->limit(3)
                ->get();
            $guesses = Product::where(['is_index' => true, 'on_sale' => true])->orderByDesc('heat')->limit(8)->get();
            return view('users.home', [
                'user' => $user,
                'orders' => $orders,
                'guesses' => $guesses,
            ]);
        } else {
            // return redirect()->route('login');
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

    // GET 修改手机页面
    public function updatePhone(User $user)
    {
        $this->authorize('update', $user);

        return view('users.update_phone', [
            'user' => $user,
        ]);
    }

    // GET 绑定手机页面
    public function bindingPhone(User $user)
    {
        $this->authorize('update', $user);

        return view('users.binding_phone', [
            'user' => $user,
        ]);
    }

    // PUT 编辑个人信息提交 & 修改密码提交 & 修改手机提交 & 绑定手机提交
    public function update(UserRequest $request, User $user, ImageUploadHandler $imageUploadHandler)
    {

        $this->authorize('update', $user);

        $data = $request->only('name', 'avatar', 'email', 'password', 'real_name', 'gender', 'qq', 'wechat', 'phone', 'facebook');

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $imageUploadHandler->uploadOriginal($request->avatar);
        }

        if ($request->has('password') && $user->password != $data['password']) {
            $data['password'] = bcrypt($data['password']);
        }

        $result = $user->update($data);

        if ($result) {
            return view('users.update_success');
        }
        return redirect()->route('users.edit', $user->id);
    }

    // POST logout
    public function logout()
    {
        Auth::logout();
    }
}
