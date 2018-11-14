<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserFavourite;
use Illuminate\Http\Request;
use App\Http\Requests\UserFavouriteRequest;

class UserFavouritesController extends Controller
{
    // GET 列表
    public function index(Request $request)
    {
        return view('user_favourites.index', [
            'favourites' => $request->user()->favourites()->with('product')->get(),
        ]);
    }
}
