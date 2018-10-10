<?php

namespace App\Http\Controllers;

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
    public function edit(UserAddress $userAddress)
    {
        $this->authorize('update', $userAddress);
        return view('user_addresses.create_and_edit', [
            'address' => $userAddress,
        ]);
    }

    // POST 创建提交
    public function store(UserAddressRequest $request)
    {
        $address = new UserAddress();
        $address->user_id = $request->user()->id;
        $address->address = $request->input('address');
        $result = $address->save();
        if ($result) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);
        }else{
            return response()->json([
                'code' => 201,
                'message' => 'fail',
            ]);
        }
    }

    // PUT 更新
    public function update(UserAddressRequest $request, UserAddress $userAddress)
    {
        $this->authorize('update', $userAddress);
        $userAddress->update($request->only(['address']));
        return redirect()->route('user_addresses.index');
    }

    // DELETE 删除
    public function destroy(UserAddress $userAddress)
    {
        $this->authorize('delete', $userAddress);
        $result = $userAddress->delete();
        if ($result) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);
        }else{
            return response()->json([
                'code' => 201,
                'message' => 'fail',
            ]);
        }
    }

    // PATCH 设置默认
    public function setDefault(Request $request, UserAddress $userAddress)
    {
        $this->authorize('update', $userAddress);
        UserAddress::where(['user_id' => $request->user()->id, 'is_default' => true])->get()->each(function($address){
            return $address->update(['is_default' => false]);
        });
        $result = $userAddress->update(['is_default' => true]);
        if ($result) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);
        }else{
            return response()->json([
                'code' => 201,
                'message' => 'fail',
            ]);
        }
    }
}
