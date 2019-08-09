<?php

namespace App\Http\Controllers;

use App\Events\UserBrowsingHistoryEvent;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\ProductRequest;
// use App\Models\ExchangeRate;
use App\Models\CustomAttrValue;
use App\Models\Product;
use App\Models\ProductParam;
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
    // GET 搜素结果
    public function search(ProductRequest $request)
    {
        $query_data = [];
        $user = $request->user();
        $is_by_param = $request->query('is_by_param');
        $param = $request->query('param');
        $value = $request->query('value');
        $query = $request->query('query');
        // Ajax request for the 1st time: route('products.search') . '?query=***&sort=***&min_price=***&max_price=***&page=1'
        // $current_page = $request->has('page') ? $request->input('page') : 1;
        // on_sale: 是否在售 + index: 综合指数

        $products = Product::where('on_sale', 1);
        $all_products = Product::where('on_sale', 1);

        if ($is_by_param == 1 && !is_null($param) && !is_null($value)) {
            $query_data['is_by_param'] = $is_by_param;
            $query_data['param'] = $param;
            $query_data['value'] = $value;
            $product_ids = ProductParam::where(['name' => $param, 'value' => $value])->get()->pluck('product_id')->toArray();
            $products = $products->whereIn('id', $product_ids);
            // $all_products = $all_products->whereIn('id', $product_ids);
        }

        if (!is_null($query)) {
            $query_data['query'] = $query;
            $products = $products->where('name_en', 'like', '%' . $query . '%')
                // ->orWhere('name_zh', 'like', '%' . $query . '%')
                ->orWhere('sub_name_en', 'like', '%' . $query . '%')
                // ->orWhere('sub_name_zh', 'like', '%' . $query . '%')
                ->orWhere('description_en', 'like', '%' . $query . '%')
                // ->orWhere('description_zh', 'like', '%' . $query . '%')
                ->orWhere('content_en', 'like', '%' . $query . '%');
                // ->orWhere('content_zh', 'like', '%' . $query . '%');
            $all_products = $all_products->where('name_en', 'like', '%' . $query . '%')
                // ->orWhere('name_zh', 'like', '%' . $query . '%')
                ->orWhere('sub_name_en', 'like', '%' . $query . '%')
                // ->orWhere('sub_name_zh', 'like', '%' . $query . '%')
                ->orWhere('description_en', 'like', '%' . $query . '%')
                // ->orWhere('description_zh', 'like', '%' . $query . '%')
                ->orWhere('content_en', 'like', '%' . $query . '%');
                // ->orWhere('content_zh', 'like', '%' . $query . '%');
        }
        // $product_count = $products->count();
        // $page_count = ceil($product_count / 5);
        // $next_page = ($current_page < $page_count) ? ($current_page + 1) : false;

        if ($request->has('min_price') && $request->input('min_price')) {
            // $min_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('min_price'), 'CNY', 'USD') : $request->input('min_price');
            $min_price = exchange_price($request->input('min_price'), 'USD', get_global_currency());
            $query_data['min_price'] = $request->input('min_price');
            $products = $products->where('price', '>', $min_price);
            $all_products = $all_products->where('price', '>', $min_price);
        }
        if ($request->has('max_price') && $request->input('max_price')) {
            // $max_price = App::isLocale('en') ? ExchangeRate::exchangePrice($request->input('max_price'), 'CNY', 'USD') : $request->input('max_price');
            $max_price = exchange_price($request->input('max_price'), 'USD', get_global_currency());
            $query_data['max_price'] = $request->input('max_price');
            $products = $products->where('price', '<', $max_price);
            $all_products = $all_products->where('price', '<', $max_price);
        }
        if ($request->has('sort')) {
            $query_data['sort'] = $request->input('sort');
            switch ($request->input('sort')) {
                case 'index':
                    $products = $products->orderByDesc('index');
                    // $all_products = $all_products->orderByDesc('index');
                    break;
                case 'heat':
                    $products = $products->orderByDesc('heat');
                    // $all_products = $all_products->orderByDesc('heat');
                    break;
                case 'latest':
                    $products = $products->orderByDesc('created_at');
                    // $all_products = $all_products->orderByDesc('created_at');
                    break;
                case 'sales':
                    $products = $products->orderByDesc('sales');
                    // $all_products = $all_products->orderByDesc('sales');
                    break;
                case 'price_asc':
                    $products = $products->orderBy('price');
                    // $all_products = $all_products->orderBy('price');
                    break;
                case 'price_desc':
                    $products = $products->orderByDesc('price');
                    // $all_products = $all_products->orderByDesc('price');
                    break;
                default:
                    $products = $products->orderByDesc('index');
                    // $all_products = $all_products->orderByDesc('index');
                    break;
            }
        } else {
            $products = $products->orderByDesc('index');
            // $all_products = $all_products->orderByDesc('index');
        }
        $products = $products->simplePaginate(12);
        $param_values = [];
        $all_products->get()->each(function (Product $product) use (&$param_values) {
            $product->params->each(function (ProductParam $productParam) use (&$param_values) {
                if (!isset($param_values[$productParam->name])) {
                    $param_values[$productParam->name] = [];
                }
                if (!isset($param_values[$productParam->name][$productParam->value])) {
                    $param_values[$productParam->name][$productParam->value] = 1;
                } else {
                    $param_values[$productParam->name][$productParam->value] += 1;
                }
            });
        });

        return view('products.search', [
            'user' => $user,
            'param_values' => $param_values,
            'products' => $products,
            'query_data' => $query_data
        ]);
    }

    // GET 模糊搜素提示结果 [10 records] [for Ajax request]
    public function searchHint(Request $request)
    {
        $query = $request->has('query') ? $request->query('query') : '';
        if (!$query) {
            throw new InvalidRequestException('Query content must not be empty.');
            /*if (App::isLocale('zh-CN')) {
                throw new InvalidRequestException('搜索内容不可为空！');
            } else {
                throw new InvalidRequestException('Query content must not be empty.');
            }*/
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
            throw new InvalidRequestException('This product is not on sale yet.');
        }

        $shipment_template = null;
        $category = $product->category()->with('parent')->first();
        $comment_count = $product->comments->count();
        $guesses = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('index')->limit(8)->get();
        $hot_sales = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('heat')->limit(8)->get();
        $best_sellers = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('sales')->limit(8)->get();
        $user = $request->user();
        $favourite = false;
        if ($user) {
            $favourite = UserFavourite::where('user_id', $user->id)->where('product_id', $product->id)->first();
        }

        // user browsing history - appending (maybe firing an event)
        $this->appendUserBrowsingHistoryCacheByProduct($product);

        $product_skus = $product->skus;
        $product_sku_ids = $product_skus->pluck('id');
        $attributes = ProductSkuAttrValue::with('sku')->whereIn('product_sku_id', $product_sku_ids)->get()->map(function (ProductSkuAttrValue $productSkuAttrValue) {
            return [
                'product_sku_id' => $productSkuAttrValue->product_sku_id,
                'name' => $productSkuAttrValue->name,
                'value' => $productSkuAttrValue->value
            ];
        })->groupBy('product_sku_id')->toArray();

        // shipment_template
        if ($request->user() && $request->user()->default_address) {
            $shipment_template = $product->get_allow_shipment_templates($request->user()->default_address->province);
            if ($shipment_template) {
                $shipment_template = $shipment_template->first();
            }
        }

        return view('products.show', [
            'category' => $category,
            'product' => $product->makeVisible(['content_en', 'content_zh']),
            'product_skus' => $product_skus,
            'attributes' => $attributes,
            'comment_count' => $comment_count,
            'guesses' => $guesses,
            'hot_sales' => $hot_sales,
            'best_sellers' => $best_sellers,
            'favourite' => $favourite,
            'shipment_template' => $shipment_template,
        ]);
    }

    // GET: 获取商品评价 [for Ajax request]
    public function comment(Request $request, Product $product)
    {
        if ($product->on_sale == 0) {
            throw new InvalidRequestException('This product is not on sale yet.');
        }

        $current_page = $request->has('page') ? $request->input('page') : 1;
        if (preg_match('/^\d+$/', $current_page) != 1) {
            throw new InvalidRequestException('The parameter page must be an integer.');
            /*if (App::isLocale('zh-CN')) {
                throw new InvalidRequestException('页码参数必须为数字！');
            } else {
                throw new InvalidRequestException('The parameter page must be an integer.');
            }*/
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

    // GET: 定制商品详情
    public function customShow(Request $request, Product $product)
    {
        if ($product->on_sale == 0) {
            throw new InvalidRequestException('This product is not on sale yet');
        }

        if ($product->type != Product::PRODUCT_TYPE_CUSTOM) {
            throw new InvalidRequestException('The product type is not custom');
        }

        return view('products.custom', [
            'product' => $product
        ]);
    }

    // POST: 定制商品提交
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

    // PUT: 定制商品修改
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
