<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFavourite;
use Illuminate\Http\Request;
use App\Http\Requests\AddUserFavouriteRequest;

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
    public function store(AddUserFavouriteRequest $request)
    {
        $userFavourite = new UserFavourite();
        $userFavourite->user_id = $request->user()->id;
        $userFavourite->product_id = $request->get('product_id');
        $result = $userFavourite->save();
        if ($result) {
            die('ok');
        } else {
            die('fail');
        }
    }

    // DELETE 删除
    public function destroy(Request $request, UserFavourite $userFavourite)
    {
        if ($userFavourite->user_id == $request->user()->id) {
            $result = $userFavourite->delete();
            if($result){
                die('success');
            }else{
                die('fail');
            }
        }else{
            die('unauthorized');
        }
    }
}
