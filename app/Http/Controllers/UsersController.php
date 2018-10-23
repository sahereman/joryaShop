<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
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
                ->where('status', '<>', 'closed')
                ->orderByDesc('created_at')
                ->limit(5)
                ->get();
            $guesses = Product::where(['is_index' => true, 'on_sale' => true])->orderByDesc('heat')->limit(8)->get();
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

    // PUT 修改用户密码
    public function updatePassword(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $this->validate($request, [
            'password_original' => [
                'bail',
                'required',
                'string',
                'min:6',
                function ($attribute, $value, $fail) use ($user) {
                    $userData = $user->makeVisible('password')->toArray();
                    if (!Hash::check($value, $userData['password'])) {
                        $fail('原密码不正确');
                    }
                },
            ],
            'password' => 'required|string|min:6|confirmed',
        ], [], [
            'password_original' => '原密码',
            'password' => '新密码',
        ]);
        $result = $user->update([
            'password' => bcrypt($request->input('password')),
        ]);
        if ($result) {
            return view('users.password_success', [
                'user' => $user,
            ]);
        }
        return redirect()->route('users.password', $user->id);
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

        $data = $request->only('avatar', 'email', 'password', 'real_name', 'gender', 'qq', 'wechat', 'phone', 'facebook');
        // $data = $request->only('name', 'avatar', 'email', 'password', 'real_name', 'gender', 'qq', 'wechat', 'phone', 'facebook');

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
