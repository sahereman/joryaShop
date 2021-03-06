<?php

namespace App\Http\Controllers;

use App\Events\OrderCompletedEvent;
use App\Handlers\ImageUploadHandler;
use App\Http\Requests\ImageUploadRequest;
use App\Models\Article;
use App\Models\Banner;
use App\Models\CountryProvince;
use App\Models\DiscountProduct;
use App\Models\ExchangeRate;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductParam;
use App\Models\User;
use App\Models\UserMoneyBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IndexController extends Controller
{

    public function test(Request $request)
    {
        $order = Order::latest()->first();
        return view('emails.order', [
            'order' => $order
        ]);
        dd('test');
    }

    public function seoUrl(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->first();
        if ($product instanceof Product) {
            $product_ctl = new ProductsController();
            return $product_ctl->show($request, $product);
        }

        $product_category = ProductCategory::where('slug', $slug)->first();
        if ($product_category instanceof ProductCategory) {
            $product_category_ctl = new ProductCategoriesController();
            return $product_category_ctl->index($request, $product_category);
        }

        $article = Article::where('slug', $slug)->first();
        if ($article instanceof Article) {
            $article_ctl = new ArticlesController();
            return $article_ctl->show($request, $article->slug);
        }

        throw new NotFoundHttpException();
    }

    public function root(Request $request)
    {
        $pc_banners = Banner::where('type', 'index')->orderBy('sort')->get();
        $mobile_banners = Banner::where('type', 'mobile')->orderBy('sort')->get();
        $products = [];
        $categories = ProductCategory::where('parent_id', '<>', 0)->where('is_index', 1)->orderBy('sort')->limit(4)->get()->each(function (ProductCategory $category, $key) use (&$products) {
            $products[$key]['category'] = $category;
            $products[$key]['products'] = $category->products()->where('is_index', 1)->orderByDesc('index')->limit(8)->get();
        });

        return view('index.root', [
            'pc_banners' => $pc_banners,
            'mobile_banners' => $mobile_banners,
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    // POST 获取上传图片预览
    // $request->image ie. $request->file('image')
    public function imagePreview(ImageUploadRequest $request, ImageUploadHandler $handler)
    {
        $preview_path = $handler->uploadTemp($request->image);
        $preview_url = Storage::disk('public')->url($preview_path);
        return response()->json([
            'preview' => $preview_url,
        ]);
    }

    // POST 获取原上传图片路径+预览
    // $request->image ie. $request->file('image')
    public function imageUpload(ImageUploadRequest $request, ImageUploadHandler $handler)
    {
        $path = $handler->uploadOriginal($request->image);
        $preview_path = $handler->uploadTemp($request->image);
        $preview_url = Storage::disk('public')->url($preview_path);
        return response()->json([
            'path' => $path,
            'preview' => $preview_url,
        ]);
    }

    // POST 获取上传Avatar头像图片预览
    // $request->image ie. $request->file('image')
    public function avatarPreview(ImageUploadRequest $request, ImageUploadHandler $handler)
    {
        $preview_path = $handler->uploadAvatarPreview($request->image);
        $preview_url = Storage::disk('public')->url($preview_path);
        return response()->json([
            'preview' => $preview_url,
        ]);
    }

    // POST 获取上传Avatar头像图片路径+预览
    // $request->image ie. $request->file('image')
    public function avatarUpload(ImageUploadRequest $request, ImageUploadHandler $handler)
    {
        $path = $handler->uploadAvatar($request->image);
        $preview_path = $handler->uploadTemp($request->image);
        $preview_url = Storage::disk('public')->url($preview_path);
        return response()->json([
            'path' => $path,
            'preview' => $preview_url,
        ]);
    }

    // POST 获取评论上传图片路径+预览
    // $request->image ie. $request->file('image')
    public function commentImageUpload(ImageUploadRequest $request, ImageUploadHandler $handler)
    {
        $path = $handler->uploadCommentImage($request->image, false, false, 240, 240);
        $preview_path = $handler->uploadTemp($request->image);
        $preview_url = Storage::disk('public')->url($preview_path);
        return response()->json([
            'path' => $path,
            'preview' => $preview_url,
        ]);
    }

    // GET 修改网站语言

    /**
     * Locale options: en | zh-CN
     */
    public function localeUpdate(Request $request, string $locale = 'en')
    {
        $request->session()->put('GlobalLocale', $locale);
        if ($locale === 'zh-CN') {
            set_global_currency('CNY');
        } else {
            set_global_currency('USD');
        }
        return back();
    }

    // GET 修改币种

    /**
     * Currency options: USD | CNY
     */
    public function currencyUpdate(Request $request, string $currency = 'USD')
    {
        /*// $currencies = ExchangeRate::exchangeRates()->pluck('currency')->all();
        $currencies = ExchangeRate::exchangeRates()->pluck('currency')->toArray();
        $currency = in_array($currency, $currencies) ? $currency : 'USD';
        $request->session()->put('GlobalCurrency', $currency);*/
        set_global_currency($currency);
        return back();
    }

    // GET about us
    public function aboutUs(Request $request)
    {
        return view('pages.about_us');
    }

    // GET contact us
    public function contactUs(Request $request)
    {
        return view('pages.contact_us');
    }

    // GET why lyrical
    public function whyLyricalhair(Request $request)
    {
        return view('pages.why_lyricalhair');
    }
}
