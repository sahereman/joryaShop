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
        $user = $request->user();
        $favourites = UserFavourite::where(['user_id' => $user->id])->with('product')->get();
        return view('user_favourites.index', [
            'favourites' => $favourites,
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
        $result = $userFavourite->save();
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

    // DELETE 删除
    public function destroy(Request $request, UserFavourite $userFavourite)
    {
        $this->authorize('delete', $userFavourite);
        $userFavourite->user()->dissociate();
        $result = $userFavourite->delete();
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
