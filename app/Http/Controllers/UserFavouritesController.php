<?php

namespace App\Http\Controllers;

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

    // POST 加入收藏
    public function store(UserFavouriteRequest $request)
    {
        $user = $request->user();
        $userFavourite = new UserFavourite();
        $userFavourite->user_id = $user->id;
        $userFavourite->product_id = $request->input('product_id');
        $userFavourite->user()->associate($user);
        $userFavourite->save();
        return response()->json([]);
    }

    // DELETE 删除
    public function destroy(Request $request, UserFavourite $userFavourite)
    {
        $this->authorize('delete', $userFavourite);
        $userFavourite->user()->dissociate();
        $userFavourite->delete();
        return view('user_favourites.index', [
            'favourites' => $request->user()->favourites()->with('product')->get(),
        ]);
    }
}
