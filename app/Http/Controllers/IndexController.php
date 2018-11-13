<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\EasySmsSendRequest;
use App\Models\Cart;
use App\Models\Poster;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\PhoneNumber;

class IndexController extends Controller
{
    public function root(Request $request)
    {
        // TODO ... [投放广告页]
        $posters = Poster::where(['slug' => 'advertisement'])->latest()->limit(3)->get();

        $products = [];
        $categories = ProductCategory::where(['parent_id' => 0, 'is_index' => 1])->get();
        foreach ($categories as $category) {
            $children = $category->children;
            if ($children->isEmpty()) {
                continue;
            }
            $children_ids = $children->pluck('id')->all();
            $products[$category->id]['category'] = $category;
            $products[$category->id]['children'] = $children;
            $products[$category->id]['products'] = Product::where('is_index', 1)->whereIn('product_category_id', $children_ids)->orderByDesc('index')->limit(8)->get();
        }
        $guesses = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('heat')->limit(8)->get();

        return view('index.root', [
            'posters' => $posters,
            'products' => $products,
            'guesses' => $guesses,
        ]);
    }

    // POST 获取上传图片预览
    public function imagePreview(Request $request, ImageUploadHandler $handler)
    {
        $this->validate($request, [
            'image' => 'required|image',
        ], [], [
            'image' => '上传图片',
        ]);
        $preview_path = $handler->uploadTemp($request->image);
        $preview_url = Storage::disk('public')->url($preview_path);
        return response()->json([
            'preview' => $preview_url,
        ]);
    }

    // POST 获取原上传图片路径+预览
    public function imageUpload(Request $request, ImageUploadHandler $handler)
    {
        $this->validate($request, [
            'image' => 'required|image',
        ], [], [
            'image' => '上传图片',
        ]);
        $path = $handler->uploadOriginal($request->image);
        $preview_path = $handler->uploadTemp($request->image);
        $preview_url = Storage::disk('public')->url($preview_path);
        return response()->json([
            'path' => $path,
            'preview' => $preview_url,
        ]);
    }

    // POST 获取评论上传图片路径+预览
    public function commentImageUpload(Request $request, ImageUploadHandler $handler)
    {
        $this->validate($request, [
            'image' => 'required|image',
        ], [], [
            'image' => '上传图片',
        ]);
        $path = $handler->uploadCommentImage($request->image, false, false, 240, 240);
        $preview_path = $handler->uploadTemp($request->image);
        $preview_url = Storage::disk('public')->url($preview_path);
        return response()->json([
            'path' => $path,
            'preview' => $preview_url,
        ]);
    }

    // POST Aliyun发送短信 [目前仅用于用户注册、登录、重置密码时发送验证码]
    public function easySmsSend(EasySmsSendRequest $request)
    {
        $response = easy_sms_send($request->input('data'), $request->input('phone_number'), $request->input('country_code'));
        return response()->json($response);
    }
}
