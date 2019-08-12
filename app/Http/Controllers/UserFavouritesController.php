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
        $favourite = UserFavourite::where('user_id', $user->id)->where('product_id', $request->input('product_id'))->first();
        if(! $favourite){
            $favourite = UserFavourite::create([
                'user_id' => $user->id,
                'product_id' => $request->input('product_id'),
            ]);
        }
        if ($favourite) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
                'data' => [
                    'favourite' => $favourite->toArray(),
                ],
            ]);
        } else {
            return response()->json([
                'code' => 422,
                'message' => 'Unprocessable Entity',
            ], 422);
        }
    }

    // DELETE 删除多条收藏记录
    public function multiDelete(UserFavouriteRequest $request)
    {
        $favourite_ids = explode(',', $request->input('favourite_ids', ''));
        $is_nil = true;
        foreach ($favourite_ids as $key => $favourite_id) {
            if (UserFavourite::where(['id' => $favourite_id, 'user_id' => $request->user()->id])->exists()) {
                $is_nil = false;
                continue;
            }
            array_forget($favourite_ids, $key);
        }

        if ($is_nil) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);
        }

        $result = UserFavourite::destroy($favourite_ids);
        if ($result) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);
        } else {
            return response()->json([
                'code' => 422,
                'message' => 'Unprocessable Entity',
            ], 422);
        }
    }

    // DELETE 删除
    public function destroy(UserFavouriteRequest $request)
    {
        $favourite = UserFavourite::find($request->input('favourite_id'));
        $this->authorize('delete', $favourite);
        $favourite->user()->dissociate();
        $result = $favourite->delete();
        if ($result) {
            return response()->json([
                'code' => 200,
                'message' => 'success',
            ]);
        } else {
            return response()->json([
                'code' => 422,
                'message' => 'Unprocessable Entity',
            ], 422);
        }
    }
}
