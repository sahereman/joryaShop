<?php

namespace App\Http\Controllers;

use App\Events\UserBrowsingHistoryEvent;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\ProductRequest;
use App\Models\ExchangeRate;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductCategory;
use App\Models\ProductComment;
use App\Models\UserHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductsController extends Controller
{
    // GET 搜素结果 [仅展示页面]
    public function search(Request $request)
    {
        $this->validate($request, [
            'query' => 'bail|required|string|min:1',
            'sort' => [
                'bail',
                'sometimes',
                'nullable',
                'string',
                Rule::in(['index', 'heat', 'latest', 'sales', 'price_asc', 'price_desc'])
            ],
            'min_price' => 'bail|sometimes|nullable|numeric|lte:max_price',
            'max_price' => 'bail|sometimes|nullable|numeric|gte:min_price',
            'page' => 'sometimes|required|integer|min:1',
        ], [], [
            'query' => '搜索内容',
            'sort' => '排序方式',
            'min_price' => '最低价格',
            'max_price' => '最高价格',
            'page' => '页码',
        ]);
        // 第一次请求 route('products.search') . '?query=***' 打开待填充数据页面
        return view('products.search');
    }

    // 搜素结果 [下拉加载更多] [for Ajax request]
    public function searchMore(Request $request)
    {
        $this->validate($request, [
            'query' => 'bail|required|string|min:1',
            'sort' => [
                'bail',
                'sometimes',
                'nullable',
                'string',
                Rule::in(['index', 'heat', 'latest', 'sales', 'price_asc', 'price_desc'])
            ],
            'min_price' => 'bail|sometimes|nullable|numeric|lte:max_price',
            'max_price' => 'bail|sometimes|nullable|numeric|gte:min_price',
            'page' => 'sometimes|required|integer|min:1',
        ], [], [
            'query' => '搜索内容',
            'sort' => '排序方式',
            'min_price' => '最低价格',
            'max_price' => '最高价格',
            'page' => '页码',
        ]);
        $query = $request->query('query');
        // Ajax request for the 1st time: route('products.search') . '?query=***&sort=***&min_price=***&max_price=***&page=1'
        $current_page = $request->has('page') ? $request->input('page') : 1;
        // on_sale: 是否在售 + index: 综合指数
        $products = Product::where('on_sale', 1)
            ->where('name_en', 'like', '%' . $query . '%')
            ->orWhere('name_zh', 'like', '%' . $query . '%')
            ->orWhere('description_en', 'like', '%' . $query . '%')
            ->orWhere('description_zh', 'like', '%' . $query . '%')
            ->orWhere('content_en', 'like', '%' . $query . '%')
            ->orWhere('content_zh', 'like', '%' . $query . '%');
        $product_count = $products->count();
        $page_count = ceil($product_count / 5);
        $next_page = ($current_page < $page_count) ? ($current_page + 1) : false;

        $query_data = [];
        $query_data['query'] = $query;
        if ($request->has('min_price') && $request->input('min_price')) {
            $min_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('min_price'), 'USD', 'CNY') : $request->input('min_price');
            $query_data['min_price'] = $request->input('min_price');
            $products = $products->where('price', '>', $min_price);
        }
        if ($request->has('max_price') && $request->input('max_price')) {
            $max_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('max_price'), 'USD', 'CNY') : $request->input('max_price');
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
        $this->validate($request, [
            'query' => 'required|string|min:1',
        ], [], [
            'query' => '搜索内容',
        ]);
        $query = $request->query('query');
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
        $skus = $product->skus;
        $comment_count = $product->comments->count();
        $guesses = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('index')->limit(8)->get();
        $hot_sales = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('heat')->limit(8)->get();
        $best_sellers = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('sales')->limit(8)->get();

        // user browsing history - appending (maybe firing an event)
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

        return view('products.show', [
            'category' => $category,
            'product' => $product,
            'skus' => $skus,
            'comment_count' => $comment_count,
            'guesses' => $guesses,
            'hot_sales' => $hot_sales,
            'best_sellers' => $best_sellers,
        ]);
    }

    // GET: 获取商品评价 [for Ajax request]
    public function comment(Request $request, Product $product)
    {
        if ($product->on_sale == 0) {
            throw new InvalidRequestException('该商品尚未上架');
        }

        $this->validate($request, [
            'page' => 'sometimes|required|integer|min:1',
        ], [], [
            'page' => '页码',
        ]);
        $current_page = $request->has('page') ? $request->input('page') : 1;

        $comments = ProductComment::where('product_id', $product->id)->get();
        $comment_count = $comments->count();
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
}
