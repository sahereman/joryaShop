<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;

class UserAddressesController extends Controller
{
    // GET 列表
    public function index (Request $request)
    {
        // TODO ...
        return view('user_addresses.index', []);
    }

    // GET 创建页面
    public function create (Request $request)
    {
        return view('user_addresses.create_and_edit');
    }

    // GET 编辑页面
    public function edit (UserAddress $userAddress)
    {
        // TODO ...
        return view('user_addresses.create_and_edit', [
            'userAddress' => $userAddress,
        ]);
    }

    // POST 创建提交
    public function store (Request $request)
    {
        // TODO ...
    }

    // PUT 更新
    public function update (Request $request)
    {
        // TODO ...
    }

    // DELETE 删除
    public function destroy (UserAddress $userAddress)
    {
        // TODO ...
    }

    // PATCH 设置默认
    public function setDefault (UserAddress $userAddress)
    {
        // TODO ...
    }
}
