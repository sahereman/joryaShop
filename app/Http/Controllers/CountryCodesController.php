<?php

namespace App\Http\Controllers;

use App\Models\CountryCode;
use Illuminate\Http\Request;

class CountryCodesController extends Controller
{
    // GET 获取国家|地区码列表
    public function index(Request $request)
    {
        return response()->json([
            'country_codes' => CountryCode::all()->toArray(),
        ]);
    }
}
