<?php

namespace App\Http\Controllers\Mobile;

use App\Exceptions\InvalidRequestException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserAddressRequest;
use App\Models\Config;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UserAddressesController extends Controller
{
    // GET 列表
    public function index(Request $request)
    {
        return view('mobile.user_addresses.index', [
            'addresses' => $request->user()->addresses,
            'max' => Config::config('max_user_address_count'),
            'count' => $request->user()->addresses->count(),
        ]);
    }

    public function create(Request $request)
    {
        return view('mobile.user_addresses.create');
    }

    public function edit(Request $request, UserAddress $address)
    {
        return view('mobile.user_addresses.edit', [
            'address' => $address,
        ]);
    }

    // POST 创建提交 [for Ajax request]
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
        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'address' => $address,
            ],
        ]);
    }
}
