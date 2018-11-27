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
        $favourite = new UserFavourite();
        $favourite->user_id = $user->id;
        $favourite->product_id = $request->input('product_id');
        $favourite->user()->associate($user);
        $result = $favourite->save();
        if($result){
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);
        }else{
            return response()->json([
                'code' => 422,
                'message' => 'Unprocessable Entity',
            ], 422);
        }
    }

    // DELETE 删除
    public function destroy(Request $request, UserFavourite $favourite)
    {
        $this->authorize('delete', $favourite);
        $favourite->user()->dissociate();
        $result = $favourite->delete();
        if($result){
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);
        }else{
            return response()->json([
                'code' => 422,
                'message' => 'Unprocessable Entity',
            ], 422);
        }
    }
}
