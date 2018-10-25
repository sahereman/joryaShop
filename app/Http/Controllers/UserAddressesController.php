<?php

namespace App\Http\Controllers;

use App\Models\Config;
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
            'max' => Config::config('max_user_address_count'),
            'count' => $request->user()->addresses->count(),
        ]);
    }

    // POST 创建提交
    public function store(UserAddressRequest $request)
    {
        $user = $request->user();
        $userAddressCount = $user->addresses->count();
        $this->validate($request, [
            'address' => function($attribute, $value, $fail) use ($userAddressCount) {
                if($userAddressCount > Config::config('max_user_address_count')){
                    $fail('用户保存收货地址数量已达上限');
                }
            },
        ]);
        $userAddress = new UserAddress();
        $userAddress->user_id = $user->id;
        $userAddress->name = $request->input('name');
        $userAddress->phone = $request->input('phone');
        $userAddress->address = $request->input('address');
        if ($request->filled('is_default')) {
            UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
                ->update(['is_default' => false]);
            $userAddress->is_default = true;
        }
        $userAddress->user()->associate($user);
        $userAddress->save();
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
            'max' => Config::config('max_user_address_count'),
            'count' => $request->user()->addresses->count(),
        ]);
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
                ->where('id', '<>', $userAddress->id)
                ->update(['is_default' => false]);
            $userAddress->is_default = true;
        }
        $userAddress->save();
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
            'max' => Config::config('max_user_address_count'),
            'count' => $request->user()->addresses->count(),
        ]);
    }

    // DELETE 删除
    public function destroy(Request $request, UserAddress $userAddress)
    {
        $this->authorize('delete', $userAddress);
        if ($userAddress->is_default) {
            UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
                ->where('id', '<>', $userAddress->id)
                ->update(['is_default' => false]);
            $address = UserAddress::where('user_id', $request->user()->id)->latest('last_used_at')->first();
            $address->is_default = true;
            $address->save();
        }
        $userAddress->user()->dissociate();
        $userAddress->delete();
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
            'max' => Config::config('max_user_address_count'),
            'count' => $request->user()->addresses->count(),
        ]);
    }

    // PATCH 设置默认
    public function setDefault(Request $request, UserAddress $userAddress)
    {
        $this->authorize('update', $userAddress);
        UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
            ->where('id', '<>', $userAddress->id)
            ->update(['is_default' => false]);
        $userAddress->is_default = true;
        $userAddress->save();
        return view('user_addresses.index', [
            'addresses' => $request->user()->addresses,
            'max' => Config::config('max_user_address_count'),
            'count' => $request->user()->addresses->count(),
        ]);
    }
}
