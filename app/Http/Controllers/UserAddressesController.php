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
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'addresses' => $request->user()->addresses,
            ],
        ], 200);
    }

    // POST 创建提交
    public function store(UserAddressRequest $request)
    {
        $user = $request->user();
        $addressCount = $user->addresses->count();
        if ($addressCount >= Config::config('max_user_address_count')) {
            if (App::isLocale('en')) {
                throw new InvalidRequestException('Your address amount is up to the maximum already.');
            } else {
                throw new InvalidRequestException('用户保存收货地址数量已达上限');
            }
        }
        $address = new UserAddress();
        $address->user_id = $user->id;
        $address->name = $request->input('name');
        $address->phone = $request->input('phone');
        $address->address = $request->input('address');
        if (($request->filled('is_default') && $request->input('is_default') == 1) || $addressCount == 0) {
            UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
                ->update(['is_default' => false]);
            $address->is_default = true;
        }
        $address->user()->associate($user);
        $address->save();
        if(\Browser::isMobile()){
            return redirect()->route('mobile.user_addresses.index');
        }
        return redirect()->route('user_addresses.index');
    }

    // PUT 更新
    public function update(UserAddressRequest $request, UserAddress $address)
    {
        $this->authorize('update', $address);
        $address->name = $request->input('name');
        $address->phone = $request->input('phone');
        $address->address = $request->input('address');
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
        if(\Browser::isMobile()){
            return redirect()->route('mobile.user_addresses.index');
        }
        return redirect()->route('user_addresses.index');
    }

    // DELETE 删除
    public function destroy(Request $request, UserAddress $address)
    {
        $this->authorize('delete', $address);
        if ($address->is_default) {
            UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])
                ->where('id', '<>', $address->id)
                ->update(['is_default' => false]);
            $address = UserAddress::where('user_id', $request->user()->id)
                ->where('id', '<>', $address->id)
                ->latest('last_used_at')
                ->latest('updated_at')
                ->latest()
                ->first();
            $address->is_default = true;
            $address->save();
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
