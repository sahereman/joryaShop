<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserHistory;
use Illuminate\Http\Request;

class UserHistoriesController extends Controller
{
    public function index (Request $request)
    {
        return view('user_histories.index', []);
    }
}
