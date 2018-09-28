<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFavourite;
use Illuminate\Http\Request;

class UserFavouritesController extends Controller
{
    public function index (Request $request)
    {
        return view('user_favourites.index', []);
    }
}
