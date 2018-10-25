<?php

namespace App\Http\Controllers;

use App\Models\ShipmentCompany;
use Illuminate\Http\Request;

class ShipmentCompaniesController extends Controller
{
    // GET 获取物流公司列表
    public function index(Request $request)
    {
        return response()->json([
            'shipment_companies' => ShipmentCompany::all()->toArray(),
        ]);
    }
}
