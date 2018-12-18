<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
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
}
