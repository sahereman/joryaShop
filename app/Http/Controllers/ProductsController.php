<?php

namespace App\Http\Controllers;

use App\Events\UserBrowsingHistoryEvent;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\ProductRequest;
// use App\Models\ExchangeRate;
use App\Models\CustomAttrValue;
use App\Models\Product;
use App\Models\ProductSku;
// use App\Models\ProductCategory;
use App\Models\ProductComment;
use App\Models\ProductSkuAttrValue;
use App\Models\ProductSkuCustomAttrValue;
use App\Models\UserFavourite;
use App\Models\UserHistory;
use Illuminate\Http\Request;
// use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    // GET 搜素结果 [仅展示页面]
    public function search(ProductRequest $request)
    {
        // 第一次请求 route('products.search') . '?query=***' 打开待填充数据页面
        return view('products.search');
    }

    // GET 搜素结果 [下拉加载更多] [for Ajax request]
    public function searchMore(ProductRequest $request)
    {
        $query_data = [];
        $query = $request->query('query');
        // Ajax request for the 1st time: route('products.search') . '?query=***&sort=***&min_price=***&max_price=***&page=1'
        $current_page = $request->has('page') ? $request->input('page') : 1;
        // on_sale: 是否在售 + index: 综合指数
        if (is_null($query)) {
            $products = Product::where('on_sale', 1);
        } else {
            $query_data['query'] = $query;
            $products = Product::where('on_sale', 1)
                ->where('name_en', 'like', '%' . $query . '%')
                ->orWhere('name_zh', 'like', '%' . $query . '%')
                ->orWhere('description_en', 'like', '%' . $query . '%')
                ->orWhere('description_zh', 'like', '%' . $query . '%')
                ->orWhere('content_en', 'like', '%' . $query . '%')
                ->orWhere('content_zh', 'like', '%' . $query . '%');
        }
        $product_count = $products->count();
        $page_count = ceil($product_count / 5);
        $next_page = ($current_page < $page_count) ? ($current_page + 1) : false;

        if ($request->has('min_price') && $request->input('min_price')) {
            // $min_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('min_price'), 'CNY', 'USD') : $request->input('min_price');
            $min_price = exchange_price($request->input('min_price'), 'USD', get_global_currency());
            $query_data['min_price'] = $request->input('min_price');
            $products = $products->where('price', '>', $min_price);
        }
        if ($request->has('max_price') && $request->input('max_price')) {
            // $max_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('max_price'), 'CNY', 'USD') : $request->input('max_price');
            $max_price = exchange_price($request->input('max_price'), 'USD', get_global_currency());
            $query_data['max_price'] = $request->input('max_price');
            $products = $products->where('price', '<', $max_price);
        }
        if ($request->has('sort')) {
            $query_data['sort'] = $request->input('sort');
            switch ($request->input('sort')) {
                case 'index':
                    $products = $products->orderByDesc('index');
                    break;
                case 'heat':
                    $products = $products->orderByDesc('heat');
                    break;
                case 'latest':
                    $products = $products->orderByDesc('created_at');
                    break;
                case 'sales':
                    $products = $products->orderByDesc('sales');
                    break;
                case 'price_asc':
                    $products = $products->orderBy('price');
                    break;
                case 'price_desc':
                    $products = $products->orderByDesc('price');
                    break;
                default:
                    $products = $products->orderByDesc('index');
                    break;
            }
        } else {
            $products = $products->orderByDesc('index');
        }
        $products = $products->simplePaginate(10);

        if ($next_page == false) {
            $request_url = false;
        } else {
            $query_data['page'] = $next_page;
            $request_url = route('products.search') . '?' . http_build_query($query_data);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'products' => $products,
                'request_url' => $request_url,
            ],
        ]);
    }

    // GET 模糊搜素提示结果 [10 records] [for Ajax request]
    public function searchHint(Request $request)
    {
        $query = $request->has('query') ? $request->query('query') : '';
        if (!$query) {
            if (App::isLocale('zh-CN')) {
                throw new InvalidRequestException('搜索内容不可为空！');
            } else {
                throw new InvalidRequestException('Query content must not be empty.');
            }
        }
        // on_sale: 是否在售 + index: 综合指数
        $products = Product::where('on_sale', 1)
            ->where('name_en', 'like', '%' . $query . '%')
            ->orWhere('name_zh', 'like', '%' . $query . '%')
            ->orderByDesc('index')
            ->limit(10)
            ->get();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'products' => $products,
            ],
        ]);
    }

    // GET 商品详情
    public function show(Request $request, Product $product)
    {
        if ($product->on_sale == 0) {
            throw new InvalidRequestException('该商品尚未上架');
        }

        $category = $product->category()->with('parent')->first();
        $comment_count = $product->comments->count();
        $guesses = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('index')->limit(8)->get();
        $hot_sales = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('heat')->limit(8)->get();
        $best_sellers = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('sales')->limit(8)->get();
        $user = $request->user();
        $is_favourite = false;
        if ($user) {
            $is_favourite = UserFavourite::where('user_id', $user->id)->where('product_id', $product->id)->exists();
        }

        // user browsing history - appending (maybe firing an event)
        $this->appendUserBrowsingHistoryCacheByProduct($product);

        $product_skus = $product->skus;
        $product_sku_ids = $product_skus->pluck('id');
        $attributes = ProductSkuAttrValue::with('sku')->whereIn('product_sku_id', $product_sku_ids)->get()->groupBy('product_sku_id')->toArray();

        return view('products.show', [
            'category' => $category,
            'product' => $product->makeVisible(['content_en', 'content_zh']),
            'product_skus' => $product_skus,
            'attributes' => $attributes,
            'comment_count' => $comment_count,
            'guesses' => $guesses,
            'hot_sales' => $hot_sales,
            'best_sellers' => $best_sellers,
            'is_favourite' => $is_favourite
        ]);
    }

    // GET: 获取商品评价 [for Ajax request]
    public function comment(Request $request, Product $product)
    {
        if ($product->on_sale == 0) {
            throw new InvalidRequestException('该商品尚未上架');
        }

        $current_page = $request->has('page') ? $request->input('page') : 1;
        if (preg_match('/^\d+$/', $current_page) != 1) {
            if (App::isLocale('zh-CN')) {
                throw new InvalidRequestException('页码参数必须为数字！');
            } else {
                throw new InvalidRequestException('The parameter page must be an integer.');
            }
        }
        $comment_count = $product->comments->count();
        $page_count = ceil($comment_count / 10);
        $next_page = ($current_page < $page_count) ? ($current_page + 1) : false;
        if ($next_page == false) {
            $request_url = false;
        } else {
            $request_url = route('products.comment', [
                    'product' => $product->id,
                ]) . '?page=' . $next_page;
        }
        $comments = ProductComment::where('product_id', $product->id)->with(['user', 'orderItem.sku'])->simplePaginate(10);
        $composite_index = ProductComment::where('product_id', $product->id)->get()->average('composite_index');
        $description_index = ProductComment::where('product_id', $product->id)->get()->average('description_index');
        $shipment_index = ProductComment::where('product_id', $product->id)->get()->average('shipment_index');

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'comments' => $comments,
                'composite_index' => $composite_index,
                'description_index' => $description_index,
                'shipment_index' => $shipment_index,
                'request_url' => $request_url,
            ],
        ]);
    }

    // user browsing history - appending (maybe firing an event)
    public function appendUserBrowsingHistoryCacheByProduct(Product $product)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (Cache::has($user->id . '-user_browsing_history_count') && Cache::has($user->id . '-user_browsing_history_list') && Cache::has($user->id . '-user_browsing_history_list_stored')) {
                $browsed_at = today()->toDateString();
                $user_browsing_history_list = Cache::get($user->id . '-user_browsing_history_list');
                $user_browsing_history_list_stored = Cache::get($user->id . '-user_browsing_history_list_stored');
                if (!isset($user_browsing_history_list[$browsed_at]) || !isset($user_browsing_history_list_stored[$browsed_at])) {
                    $user_browsing_history_list[$browsed_at] = [];
                    $user_browsing_histories = UserHistory::where('user_id', $user->id)
                        ->where('browsed_at', '>=', $browsed_at)
                        ->get()
                        ->pluck('product_id')
                        ->toArray();
                    $user_browsing_history_list_stored[$browsed_at] = $user_browsing_histories;
                }
                $user_browsing_histories = array_merge($user_browsing_history_list_stored[$browsed_at], $user_browsing_history_list[$browsed_at]);
                if (!in_array($product->id, $user_browsing_histories)) {
                    $user_browsing_history_list[$browsed_at][] = $product->id;
                    Cache::increment($user->id . '-user_browsing_history_count');
                }
                Cache::forever($user->id . '-user_browsing_history_list', $user_browsing_history_list);
                Cache::forever($user->id . '-user_browsing_history_list_stored', $user_browsing_history_list_stored);
                if (Cache::get($user->id . '-user_browsing_history_count') >= 25) {
                    event(new UserBrowsingHistoryEvent($user));
                }
            }
        }
    }

    public function customShow(Request $request, Product $product)
    {
        return view('products.custom', [
            'product' => $product
        ]);
    }

    public function customStore(Request $request, Product $product)
    {
        $product_sku = ProductSku::create([
            'product_id' => $product->id,
            'name_en' => 'custom product sku of lyrical hair',
            'name_zh' => 'custom product sku of lyrical hair',
            'photo' => '',
            'price' => $request->input('price'),
            'stock' => 0,
            'sales' => 1,
        ]);

        foreach ($request->input('custom_attr_values') as $custom_attr_value) {
            $custom_attr_value_model = CustomAttrValue::first(['value' => $custom_attr_value['value']]);
            ProductSkuCustomAttrValue::create([
                'product_sku_id' => $product_sku->id,
                'name' => $custom_attr_value['name'],
                'value' => $custom_attr_value['value'],
                'sort' => $custom_attr_value_model->sort
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success'
        ]);
    }

    public function customUpdate(Request $request, Product $product)
    {
        // $product_sku = ProductSku::with('custom_attr_values')->find($request->input('product_sku_id'));
        foreach ($request->input('custom_attr_values') as $custom_attr_value) {
            $custom_attr_value_model = CustomAttrValue::first(['value' => $custom_attr_value['value']]);
            $product_sku_custom_attr_value = ProductSkuCustomAttrValue::firstOrCreate([
                'product_sku_id' => $request->input('product_sku_id'),
                'name' => $custom_attr_value['name']
            ], [
                'value' => $custom_attr_value['value'],
                'sort' => $custom_attr_value_model->sort
            ]);
            if ($product_sku_custom_attr_value->value != $custom_attr_value['value']) {
                $product_sku_custom_attr_value->update([
                    'value' => $custom_attr_value['value'],
                    'sort' => $custom_attr_value_model->sort
                ]);
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'success'
        ]);
    }
}
