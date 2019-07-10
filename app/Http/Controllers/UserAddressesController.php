<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\Config;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Http\Requests\UserAddressRequest;
use Illuminate\Support\Facades\App;

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

    // GET 获取当前用户收货地址列表 [for Ajax request]
    public function listAll(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => [
                    'addresses' => $user->addresses
                ]
            ], 200);
        }
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'addresses' => []
            ]
        ], 200);
    }

    // POST 创建提交
    public function store(UserAddressRequest $request)
    {
        $user = $request->user();
        $addressCount = $user->addresses->count();
        if ($addressCount >= Config::config('max_user_address_count')) {
            if (App::isLocale('zh-CN')) {
                throw new InvalidRequestException('用户保存收货地址数量已达上限');
            } else {
                throw new InvalidRequestException('Your address amount is up to the maximum already.');
            }
        }
        $address = new UserAddress();
        $address->user_id = $user->id;
        $address->name = $request->input('name');
        $address->phone = $request->input('phone');
        $address->country = $request->input('country');
        $address->province = $request->input('province');
        $address->city = $request->input('city');
        $address->address = $request->input('address');
        $address->zip = $request->input('zip');
        if (($request->filled('is_default') && $request->input('is_default') == 1) || $addressCount == 0) {
            UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
                ->update(['is_default' => false]);
            $address->is_default = true;
        }
        // $address->user()->associate($user);
        $address->save();
        if (\Browser::isMobile()) {
            return redirect()->route('mobile.user_addresses.index');
        }
        return redirect()->route('user_addresses.index');
    }

    // POST 创建用户收货地址 [for Ajax request]
    public function storeForAjax(UserAddressRequest $request)
    {
        $user = $request->user();
        if (!$user) {
            $address = new UserAddress();
            $address->name = $request->input('name');
            $address->phone = $request->input('phone');
            $address->country = $request->input('country');
            $address->province = $request->input('province');
            $address->city = $request->input('city');
            $address->address = $request->input('address');
            $address->zip = $request->input('zip');
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => [
                    'address' => $address
                ]
            ]);
        }
        $addressCount = $user->addresses->count();
        if ($addressCount >= Config::config('max_user_address_count')) {
            if (App::isLocale('zh-CN')) {
                throw new InvalidRequestException('用户保存收货地址数量已达上限');
            } else {
                throw new InvalidRequestException('Your address amount is up to the maximum already.');
            }
        }
        $address = new UserAddress();
        $address->user_id = $user->id;
        $address->name = $request->input('name');
        $address->phone = $request->input('phone');
        $address->country = $request->input('country');
        $address->province = $request->input('province');
        $address->city = $request->input('city');
        $address->address = $request->input('address');
        $address->zip = $request->input('zip');
        if (($request->filled('is_default') && $request->input('is_default') == 1) || $addressCount == 0) {
            UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
                ->update(['is_default' => false]);
            $address->is_default = true;
        }
        // $address->user()->associate($user);
        $address->save();
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'address' => $address,
            ],
        ]);
    }

    // PUT 更新
    public function update(UserAddressRequest $request, UserAddress $address)
    {
        $this->authorize('update', $address);
        $address->name = $request->input('name');
        $address->phone = $request->input('phone');
        $address->country = $request->input('country');
        $address->province = $request->input('province');
        $address->city = $request->input('city');
        $address->address = $request->input('address');
        $address->zip = $request->input('zip');
        if ($request->filled('is_default') && $request->input('is_default') == 1) {
            UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
                ->where('id', '<>', $address->id)
                ->update(['is_default' => false]);
            $address->is_default = true;
        } elseif ($request->filled('is_default') && $request->input('is_default') == 0) {
            $default_address = UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
                ->where('id', '<>', $address->id)
                ->first();
            if (!$default_address) {
                $default_address = UserAddress::where(['user_id' => $request->user()->id, 'is_default' => false])
                    ->where('id', '<>', $address->id)
                    ->orderByDesc('last_used_at')
                    ->first();
                if ($default_address) {
                    $default_address->update(['is_default' => true]);
                }
            }
            $address->is_default = false;
        }
        $address->save();
        if (\Browser::isMobile()) {
            return redirect()->route('mobile.user_addresses.index');
        }
        return redirect()->route('user_addresses.index');
    }

    // DELETE 删除
    public function destroy(Request $request, UserAddress $address)
    {
        $this->authorize('delete', $address);
        $user = $request->user();
        if ($user->addresses->count() > 1) {
            if ($address->is_default) {
                UserAddress::where(['user_id' => $user->id, 'is_default' => true])
                    ->where('id', '<>', $address->id)
                    ->update(['is_default' => false]);
                $address_model = UserAddress::where('user_id', $user->id)
                    ->where('id', '<>', $address->id)
                    ->latest('last_used_at')
                    ->latest('updated_at')
                    ->latest()
                    ->first();
                $address_model->is_default = true;
                $address_model->save();
            }
        }
        $address->user()->dissociate();
        $result = $address->delete();
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

    // PATCH 设置默认
    public function setDefault(Request $request, UserAddress $address)
    {
        $this->authorize('update', $address);
        UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
            ->where('id', '<>', $address->id)
            ->update(['is_default' => false]);
        $address->is_default = true;
        $result = $address->save();
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
}
