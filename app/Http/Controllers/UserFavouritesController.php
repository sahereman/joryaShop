<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFavourite;
use Illuminate\Http\Request;

class UserFavouritesController extends Controller
{
    // GET 列表
    public function index (Request $request)
    {
        return view('user_favourites.index', []);
    }

    // POST 加入收藏
    public function store (Request $request)
    {
        // TODO ...
    }

    // DELETE 删除
    public function destroy (UserFavourite $userFavourite)
    {
        // TODO ...
    }
}
