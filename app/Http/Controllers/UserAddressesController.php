<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Http\Requests\UserAddressRequest;

class UserAddressesController extends Controller
{
    // GET 列表
    public function index(Request $request)
    {
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
        ]);
    }

    // GET 创建页面
    public function create(Request $request)
    {
        return view('user_addresses.create_and_edit', [
            'address' => new UserAddress(),
        ]);
    }

    // GET 编辑页面
    public function edit(Request $request, UserAddress $userAddress)
    {
        $this->authorize('update', $userAddress);
        return view('user_addresses.create_and_edit', [
            'address' => $userAddress,
        ]);
    }

    // POST 创建提交
    public function store(UserAddressRequest $request)
    {
        $user = $request->user();
        $userAddress = new UserAddress();
        $userAddress->user_id = $user->id;
        $userAddress->name = $request->input('name');
        $userAddress->phone = $request->input('phone');
        $userAddress->address = $request->input('address');
        if ($request->filled('is_default')) {
            UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
                ->update(['is_default' => 'false']);
            $userAddress->is_default = true;
        }
        $userAddress->user()->associate($user);
        $userAddress->save();
        return response()->json([]);
    }

    // PUT 更新
    public function update(UserAddressRequest $request, UserAddress $userAddress)
    {
        $this->authorize('update', $userAddress);
        $userAddress->name = $request->input('name');
        $userAddress->phone = $request->input('phone');
        $userAddress->address = $request->input('address');
        if($request->filled('is_default')){
            UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
                ->update(['is_default' => 'false']);
            $userAddress->is_default = true;
        }
        $result = $userAddress->save();
        if ($result) {
            return redirect()->route('user_addresses.index');
        } else {
            return redirect()->back();
        }
    }

    // DELETE 删除
    public function destroy(Request $request, UserAddress $userAddress)
    {
        $this->authorize('delete', $userAddress);
        if ($userAddress->is_default) {
            UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
                ->update(['is_default' => 'false']);
            $address = UserAddress::where('user_id', $request->user()->id)->latest('last_used_at')->first();
            $address->is_default = true;
            $address->save();
        }
        $userAddress->user()->dissociate();
        $userAddress->delete();
        return response()->json([]);
    }

    // PATCH 设置默认
    public function setDefault(Request $request, UserAddress $userAddress)
    {
        $this->authorize('update', $userAddress);
        UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
            ->update(['is_default' => 'false']);
        $userAddress->is_default = true;
        $userAddress->save();
        return response()->json([]);
    }
}
