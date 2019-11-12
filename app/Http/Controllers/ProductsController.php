<?php

namespace App\Http\Controllers;

use App\Events\UserBrowsingHistoryEvent;
use App\Exceptions\InvalidRequestException;
use App\Http\Requests\ProductRequest;
// use App\Models\ExchangeRate;
use App\Mail\SendShareEmail;
use App\Models\Cart;
use App\Models\CustomAttr;
use App\Models\CustomAttrValue;
use App\Models\EmailLog;
use App\Models\Param;
use App\Models\ParamValue;
use App\Models\Product;
use App\Models\ProductParam;
use App\Models\ProductSku;
// use App\Models\ProductCategory;
use App\Models\ProductComment;
use App\Models\ProductSkuAttrValue;
use App\Models\ProductSkuCustomAttrValue;
use App\Models\ProductSkuDuplicateAttrValue;
use App\Models\ProductSkuRepairAttrValue;
use App\Models\User;
use App\Models\UserFavourite;
use App\Models\UserHistory;
use Illuminate\Http\Request;
// use Illuminate\Support\Carbon;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    // GET 搜素结果
    public function search(ProductRequest $request)
    {
        $query_data = [];
        $query_param_values = [];
        $user = $request->user();
        $queries = $request->query();
        $is_by_param = $request->query('is_by_param');
        foreach ($queries as $key => $value) {
            if (strpos($key, 'param-') === 0) {
                $param = str_replace('_', ' ', substr($key, 6));
                $query_data[$key] = $value;
                $query_param_values[$param] = $value;
            }
        }
        $query = $request->query('query');

        $products = Product::where('on_sale', 1);
        $all_products = Product::where('on_sale', 1);

        if ($is_by_param == 1 && count($query_param_values) > 0) {
            $query_data['is_by_param'] = $is_by_param;
            $product_ids = [];
            foreach ($query_param_values as $param => $value) {
                if ($product_ids == []) {
                    $product_ids = ProductParam::where(['name' => $param, 'value' => $value])->get()->pluck('product_id')->toArray();
                } else {
                    $product_ids = ProductParam::where(['name' => $param, 'value' => $value])->whereIn('product_id', $product_ids)->get()->pluck('product_id')->toArray();
                }
            }
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

        /*if ($request->has('min_price') && $request->input('min_price')) {
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
        }*/

        $query_data['order'] = $request->input('order', 'desc');

        if ($request->has('sort')) {
            $query_data['sort'] = $request->input('sort');
            switch ($request->input('sort')) {
                case 'index':
                    if ($query_data['order'] == 'asc') {
                        $products = $products->orderBy('index');
                    } else {
                        $products = $products->orderByDesc('index');
                    }
                    // $all_products = $all_products->orderByDesc('index');
                    break;
                case 'heat':
                    if ($query_data['order'] == 'asc') {
                        $products = $products->orderBy('heat');
                    } else {
                        $products = $products->orderByDesc('heat');
                    }
                    // $all_products = $all_products->orderByDesc('heat');
                    break;
                case 'latest':
                    if ($query_data['order'] == 'asc') {
                        $products = $products->orderBy('created_at');
                    } else {
                        $products = $products->orderByDesc('created_at');
                    }
                    // $all_products = $all_products->orderByDesc('created_at');
                    break;
                case 'sales':
                    if ($query_data['order'] == 'asc') {
                        $products = $products->orderBy('sales');
                    } else {
                        $products = $products->orderByDesc('sales');
                    }
                    // $all_products = $all_products->orderByDesc('sales');
                    break;
                case 'price':
                    if ($query_data['order'] == 'asc') {
                        $products = $products->orderBy('price');
                    } else {
                        $products = $products->orderByDesc('price');
                    }
                    // $all_products = $all_products->orderBy('price');
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
        Param::orderByDesc('sort')->get()->each(function (Param $param) use (&$param_values) {
            $param_values[$param->name] = [];
            $param->values->each(function (ParamValue $paramValue) use (&$param_values, $param) {
                $param_values[$param->name][$paramValue->value] = 0;
            });
        });
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
            'query_param_values' => $query_param_values,
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
        $guesses = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('index')->limit(9)->get();
        // $hot_sales = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('heat')->limit(8)->get();
        // $best_sellers = Product::where(['is_index' => 1, 'on_sale' => 1])->orderByDesc('sales')->limit(8)->get();
        $user = $request->user();
        $favourite = false;
        if ($user) {
            $favourite = UserFavourite::where('user_id', $user->id)->where('product_id', $product->id)->first();
        }

        // user browsing history - appending (maybe firing an event)
        $this->appendUserBrowsingHistoryCacheByProduct($product);

        $product_skus = $product->skus;
        $product_sku_ids = $product_skus->pluck('id');
        $attributes = [];
        $attr_values = [];
        ProductSkuAttrValue::with('sku', 'attr')->whereIn('product_sku_id', $product_sku_ids)->orderByDesc('sort')->get()->each(function (ProductSkuAttrValue $productSkuAttrValue) use (&$attributes, &$attr_values) {
            $attr_name = $productSkuAttrValue->name;
            $attr_value = $productSkuAttrValue->value;
            if (!isset($attr_values[$attr_name]) || !in_array($attr_value, $attr_values[$attr_name])) {
                $attribute = [
                    // 'product_sku_id' => $productSkuAttrValue->product_sku_id,
                    'name' => $attr_name,
                    'value' => $attr_value,
                    // 'stock' => $productSkuAttrValue->sku->stock,
                    // 'price' => $productSkuAttrValue->sku->price,
                    'delta_price' => $productSkuAttrValue->sku->delta_price,
                ];
                if ($productSkuAttrValue->attr->has_photo) {
                    $attribute['photo_url'] = $productSkuAttrValue->sku->photo_url;
                }
                $attributes[$attr_name][$attr_value] = $attribute;
                $attr_values[$attr_name][] = $attr_value;
            }
        });
        ksort($attributes);
        foreach ($attributes as $attr_name => &$attr_values) {
            $attr_keys = array_keys($attr_values);
            if (preg_match('/^\d+/', $attr_keys[0])) {
                ksort($attr_values, SORT_NUMERIC);
            } else {
                ksort($attr_values, SORT_REGULAR);
            }
            $attr_values = array_values($attr_values);
        }
        /*$product_skus->each(function (ProductSku $productSku) use (&$attributes) {
            $attributes[$productSku->id]['stock'] = $productSku->stock;
            $attributes[$productSku->id]['price'] = $productSku->price;
            $attributes[$productSku->id]['delta_price'] = $productSku->delta_price;
        });*/

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
            // 'product_skus' => $product_skus,
            'attributes' => $attributes,
            'comment_count' => $comment_count,
            'guesses' => $guesses,
            // 'hot_sales' => $hot_sales,
            // 'best_sellers' => $best_sellers,
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
        $page_count = ceil($comment_count / 6);
        $previous_page = ($current_page > 1) ? ($current_page - 1) : false;
        $next_page = ($current_page < $page_count) ? ($current_page + 1) : false;
        if ($previous_page == false) {
            $previous_url = false;
        } else {
            $previous_url = route('products.comment', [
                    'product' => $product->id,
                ]) . '?page=' . $previous_page;
        }
        if ($next_page == false) {
            $next_url = false;
        } else {
            $next_url = route('products.comment', [
                    'product' => $product->id,
                ]) . '?page=' . $next_page;
        }
        $comments = ProductComment::where('product_id', $product->id)->with(['user', 'orderItem.sku'])->simplePaginate(10);
        // $composite_index = ProductComment::where('product_id', $product->id)->get()->average('composite_index');
        // $composite_index = bcdiv(bcmul($composite_index, 100), 5);
        // $description_index = ProductComment::where('product_id', $product->id)->get()->average('description_index');
        // $shipment_index = ProductComment::where('product_id', $product->id)->get()->average('shipment_index');

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => [
                'comments' => $comments,
                // 'composite_index' => $composite_index,
                // 'description_index' => $description_index,
                // 'shipment_index' => $shipment_index,
                'previous_url' => $previous_url,
                'next_url' => $next_url,
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

        // $custom_attrs = CustomAttr::with('values')->orderByDesc('sort')->get();
        /*$custom_attrs->each(function (CustomAttr $customAttr) {
            $customAttr->values = $customAttr->values()->orderByDesc('sort')->get();
        });*/
        // $grouped_custom_attrs = $custom_attrs->groupBy('type');
        $grouped_custom_attrs = CustomAttr::with('values')->orderByDesc('sort')->get()->groupBy('type');

        /*根据模型中静态数组的顺序排序*/
        $sorted_grouped_custom_attrs = [];
        foreach (CustomAttr::$customAttrTypeMap as $item) {
            if (isset($grouped_custom_attrs[$item])) {
                $sorted_grouped_custom_attrs[$item] = $grouped_custom_attrs[$item];
            }
        }
        $sorted_grouped_custom_attrs = collect($sorted_grouped_custom_attrs);

        $custom_attr_types = $sorted_grouped_custom_attrs->keys()->toArray();
        return view('products.custom', [
            'product' => $product,
            'custom_attr_types' => $custom_attr_types,
            'grouped_custom_attrs' => $sorted_grouped_custom_attrs,
        ]);
    }

    // POST: 定制商品提交
    public function customStore(Request $request, Product $product)
    {
        $user = $request->user();

        $delta_price = 0;
        $custom_attr_value_ids = $request->input('custom_attr_value_ids');
        $custom_attr_value_ids = explode(',', $custom_attr_value_ids);

        $flag = false;
        $required_custom_attrs = CustomAttr::where(['is_required' => 1])->get();
        $required_custom_attrs->each(function (CustomAttr $customAttr) use ($custom_attr_value_ids, &$flag) {
            $intersect = $customAttr->values->pluck('id')->intersect($custom_attr_value_ids);
            if ($intersect->isEmpty()) {
                $flag = true;
            }
        });
        if ($flag) {
            throw new InvalidRequestException('Please make sure that you have set every REQUIRED custom attribute.');
        }

        $custom_attr_values = CustomAttrValue::with('attr')->orderByDesc('sort')->whereIn('id', $custom_attr_value_ids)->get();
        $custom_attr_values->each(function (CustomAttrValue $customAttrValue) use (&$delta_price) {
            $delta_price = bcadd($delta_price, $customAttrValue->delta_price, 2);
        });

        $product_sku = ProductSku::create([
            'product_id' => $product->id,
            'name_en' => 'custom product sku of lyrical hair',
            'name_zh' => 'custom product sku of lyrical hair',
            'photo' => '',
            'delta_price' => $delta_price,
            'stock' => 100, // 暂定数值，占位用，便于客户在购物车内追加购买数量
            'sales' => 1,
        ]);

        $product_sku_id = $product_sku->id;

        $custom_attr_values->each(function (CustomAttrValue $customAttrValue) use ($product_sku_id) {
            ProductSkuCustomAttrValue::create([
                'product_sku_id' => $product_sku_id,
                'type' => $customAttrValue->attr->type,
                'name' => $customAttrValue->attr->name,
                'value' => $customAttrValue->value,
                'sort' => ($customAttrValue->sort + $customAttrValue->attr->sort)
            ]);
        });

        /*$delta_price = 0;
        $custom_attr_values = $request->input('custom_attr_values');
        // $custom_attr_values = explode(',', $custom_attr_values);

        foreach ($custom_attr_values as $custom_attr_value) {
            $delta_price = bcadd($delta_price, $custom_attr_value['delta_price'], 2);
        }

        $product_sku = ProductSku::create([
            'product_id' => $product->id,
            'name_en' => 'custom product sku of lyrical hair',
            'name_zh' => 'custom product sku of lyrical hair',
            'photo' => '',
            'delta_price' => $delta_price,
            'stock' => 100, // 暂定数值，占位用，便于客户在购物车内追加购买数量
            'sales' => 1,
        ]);

        $product_sku_id = $product_sku->id;
        // $custom_attr_value_count = count($custom_attr_values);

        foreach ($custom_attr_values as $key => $custom_attr_value) {
            ProductSkuCustomAttrValue::create([
                'product_sku_id' => $product_sku_id,
                'type' => $custom_attr_value['type'],
                'name' => $custom_attr_value['name'],
                'value' => $custom_attr_value['value'],
                // 'sort' => (integer)($custom_attr_value_count - $key)
                'sort' => $custom_attr_value['sort']
            ]);
        }*/

        if ($user) {
            Cart::create([
                'user_id' => $user->id,
                'product_sku_id' => $product_sku_id,
                'number' => 1
            ]);
        } else {
            $cart = session('cart', []);
            // $cart = Session::get('cart', []);

            if (isset($cart[$product_sku_id])) {
                $cart[$product_sku_id] += 1;
            } else {
                $cart[$product_sku_id] = 1;
            }

            session(['cart' => $cart]);
            // Session::put('cart', $cart);
            // Session::put(['cart' => $cart]);
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

    // POST: 复制商品提交
    public function duplicateStore(Request $request, Product $product)
    {
        $user = $request->user();

        $delta_price = 0;
        $duplicate_attr_values = $request->input('duplicate_attr_values');
        // $duplicate_attr_values = explode(',', $duplicate_attr_values);

        foreach ($duplicate_attr_values as $duplicate_attr_value) {
            $delta_price = bcadd($delta_price, $duplicate_attr_value['delta_price'], 2);
        }

        $product_sku = ProductSku::create([
            'product_id' => $product->id,
            'name_en' => 'duplicate product sku of lyrical hair',
            'name_zh' => 'duplicate product sku of lyrical hair',
            'photo' => '',
            'delta_price' => $delta_price,
            'stock' => 100, // 暂定数值，占位用，便于客户在购物车内追加购买数量
            'sales' => 1,
        ]);

        $product_sku_id = $product_sku->id;
        // $duplicate_attr_value_count = count($duplicate_attr_values);

        foreach ($duplicate_attr_values as $key => $duplicate_attr_value) {
            ProductSkuDuplicateAttrValue::create([
                'product_sku_id' => $product_sku_id,
                'name' => $duplicate_attr_value['name'],
                'value' => $duplicate_attr_value['value'],
                // 'sort' => (integer)($duplicate_attr_value_count - $key)
                'sort' => $duplicate_attr_value['sort']
            ]);
        }

        if ($user) {
            Cart::create([
                'user_id' => $user->id,
                'product_sku_id' => $product_sku_id,
                'number' => 1
            ]);
        } else {
            $cart = session('cart', []);
            // $cart = Session::get('cart', []);

            if (isset($cart[$product_sku_id])) {
                $cart[$product_sku_id] += 1;
            } else {
                $cart[$product_sku_id] = 1;
            }

            session(['cart' => $cart]);
            // Session::put('cart', $cart);
            // Session::put(['cart' => $cart]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success'
        ]);
    }

    // PUT: 复制商品修改
    public function duplicateUpdate(Request $request, Product $product)
    {
        // $product_sku = ProductSku::with('duplicate_attr_values')->find($request->input('product_sku_id'));
        foreach ($request->input('duplicate_attr_values') as $duplicate_attr_value) {
            $product_sku_duplicate_attr_value = ProductSkuDuplicateAttrValue::first([
                'product_sku_id' => $request->input('product_sku_id'),
                'name' => $duplicate_attr_value['name']
            ]);
            if ($product_sku_duplicate_attr_value->value != $duplicate_attr_value['value']) {
                $product_sku_duplicate_attr_value->update([
                    'value' => $duplicate_attr_value['value'],
                    'sort' => $duplicate_attr_value['sort']
                ]);
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'success'
        ]);
    }

    // POST: 修复商品提交
    public function repairStore(Request $request, Product $product)
    {
        $user = $request->user();

        $delta_price = 0;
        $repair_attr_values = $request->input('repair_attr_values');
        // $repair_attr_values = explode(',', $repair_attr_values);

        foreach ($repair_attr_values as $repair_attr_value) {
            $delta_price = bcadd($delta_price, $repair_attr_value['delta_price'], 2);
        }

        $product_sku = ProductSku::create([
            'product_id' => $product->id,
            'name_en' => 'repair product sku of lyrical hair',
            'name_zh' => 'repair product sku of lyrical hair',
            'photo' => '',
            'delta_price' => $delta_price,
            'stock' => 100, // 暂定数值，占位用，便于客户在购物车内追加购买数量
            'sales' => 1,
        ]);

        $product_sku_id = $product_sku->id;
        // $repair_attr_value_count = count($repair_attr_values);

        foreach ($repair_attr_values as $key => $repair_attr_value) {
            ProductSkuRepairAttrValue::create([
                'product_sku_id' => $product_sku_id,
                'name' => $repair_attr_value['name'],
                'value' => $repair_attr_value['value'],
                // 'sort' => (integer)($repair_attr_value_count - $key)
                'sort' => $repair_attr_value['sort']
            ]);
        }

        if ($user) {
            Cart::create([
                'user_id' => $user->id,
                'product_sku_id' => $product_sku_id,
                'number' => 1
            ]);
        } else {
            $cart = session('cart', []);
            // $cart = Session::get('cart', []);

            if (isset($cart[$product_sku_id])) {
                $cart[$product_sku_id] += 1;
            } else {
                $cart[$product_sku_id] = 1;
            }

            session(['cart' => $cart]);
            // Session::put('cart', $cart);
            // Session::put(['cart' => $cart]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success'
        ]);
    }

    // PUT: 修复商品修改
    public function repairUpdate(Request $request, Product $product)
    {
        // $product_sku = ProductSku::with('repair_attr_values')->find($request->input('product_sku_id'));
        foreach ($request->input('repair_attr_values') as $repair_attr_value) {
            $product_sku_repair_attr_value = ProductSkuRepairAttrValue::first([
                'product_sku_id' => $request->input('product_sku_id'),
                'name' => $repair_attr_value['name']
            ]);
            if ($product_sku_repair_attr_value->value != $repair_attr_value['value']) {
                $product_sku_repair_attr_value->update([
                    'value' => $repair_attr_value['value'],
                    'sort' => $repair_attr_value['sort']
                ]);
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'success'
        ]);
    }

    // POST: 发送商品分享邮件 [for Ajax request]
    public function share(ProductRequest $request, Product $product)
    {
        $to_email = $request->input('to_email');
        $from_email = $request->input('from_email');
        $subject = $request->input('subject');
        $body = $request->input('body');
        $user = new User();
        $user->name = 'unknown';
        $user->email = $to_email;
        Mail::to($user)->queue(new SendShareEmail($product, $from_email, $subject, $body));

        /*EmailLog::updateOrCreate([
            'email' => $to_email
        ], [
            'content' => "Subject: {$subject}. " . view('emails.share', [
                    'product' => $product,
                    'from_email' => $from_email,
                    'body' => $body
                ]),
            'sent_at' => Carbon::now()->toDateTimeString()
        ]);*/
        if ($email_log = EmailLog::where(['email' => $to_email])->first()) {
            $email_log->update([
                'content' => "Subject: {$subject}. " . view('emails.share', [
                        'product' => $product,
                        'from_email' => $from_email,
                        'body' => $body
                    ]),
                'sent_at' => Carbon::now()->toDateTimeString()
            ]);
        } else {
            EmailLog::create([
                'email' => $to_email,
                'content' => "Subject: {$subject}. " . view('emails.share', [
                        'product' => $product,
                        'from_email' => $from_email,
                        'body' => $body
                    ]),
                'sent_at' => Carbon::now()->toDateTimeString()
            ]);
        }

        return response()->json([
            'code' => 200,
            'message' => 'success'
        ], 200);
    }

    // POST: 筛选可用的 SKU 属性值
    public function searchBySkuAttr(ProductRequest $request, Product $product)
    {
        $data = [
            'selected' => [],
            'data' => [],
        ];
        $product_attrs = $product->attrs;
        $product_attr_names = $product_attrs->pluck('name', 'id')->toArray();
        $product_attr_ids = $product_attrs->pluck('id', 'name')->toArray();
        $product_skus = $product->skus()->with('attr_values')->get();
        $product_sku_ids = $product_skus->pluck('id')->toArray();
        $product_sku_attr_value_collection = ProductSkuAttrValue::whereIn('product_sku_id', $product_sku_ids)->get();
        $product_sku_attr_values = $request->input('product_sku_attr_values');
        $cache_key = '';
        if ($product_sku_attr_values) {
            asort($product_sku_attr_values);
            foreach ($product_sku_attr_values as $product_attr_name => $product_sku_attr_value) {
                $cache_key .= $product_sku_attr_value;
                if ($product_sku_attr_value && !in_array($product_attr_name, $product_attr_names)) {
                    break;
                }
                if ($product_sku_attr_value) {
                    $product_attr_id = $product_attr_ids[$product_attr_name];
                    $product_sku_ids = $product_sku_attr_value_collection->whereIn('product_sku_id', $product_sku_ids)->where('product_attr_id', $product_attr_id)->where('value', $product_sku_attr_value)->pluck('product_sku_id')->toArray();
                }
                if (!$product_sku_ids) {
                    break;
                }
            }
        } else {
            $cache_key = 'initialization';
        }

        // try to retrieve data from product_sku_attr_value_cache
        if (Cache::has($product->id . 'product_sku_attr_value_cache') && $cache_key) {
            $product_sku_attr_value_cache = Cache::get($product->id . 'product_sku_attr_value_cache', []);
            if (isset($product_sku_attr_value_cache[$cache_key])) {
                return response()->json([
                    'message' => 'success',
                    'data' => $product_sku_attr_value_cache[$cache_key],
                ], 200);
            }
        }

        if (count($product_sku_ids) > 0) {
            /*$selected_sku = $product_skus->filter(function ($product_sku) {
                return $product_sku->stock > 0;
            })->whereIn('id', $product_sku_ids)->first();*/
            $selected_sku = $product_skus->whereIn('id', $product_sku_ids)->first();
        } else {
            /*$selected_sku = $product_skus->filter(function ($product_sku) {
                return $product_sku->stock > 0;
            })->first();*/
            $selected_sku = $product_skus->first();
        }
        if ($selected_sku) {
            $selected_sku_attr_values = $selected_sku->attr_values->pluck('value', 'product_attr_id')->toArray();
            $data['selected']['sku'] = $selected_sku->toArray();
        } else {
            $selected_sku_attr_values = [];
            $data['selected']['sku'] = [];
        }
        $product_sku_ids = $product_skus->pluck('id')->toArray();
        $sku_attr_values = [];
        foreach ($selected_sku_attr_values as $product_attr_id => $value) {
            $product_attr_name = $product_attr_names[$product_attr_id];
            $sku_attr_values[$product_attr_name] = [];
            $data['selected'][$product_attr_name] = $value;
            $product_sku_attr_value_collection->where('product_attr_id', $product_attr_id)->sortBy('value')->each(function (ProductSkuAttrValue $productSkuAttrValue) use (&$data, $product_attr_names, $product_skus, $product_sku_ids, $product_sku_attr_value_collection, $selected_sku_attr_values, $product_attr_id, $product_attr_name, &$sku_attr_values) {
                $product_sku_attr_value = $productSkuAttrValue->value;
                $selected_sku_attr_values[$product_attr_id] = $product_sku_attr_value;
                $sku_ids = $product_sku_ids;
                foreach ($selected_sku_attr_values as $product_attr_id => $value) {
                    $sku_ids = $product_sku_attr_value_collection->whereIn('product_sku_id', $sku_ids)->where('product_attr_id', $product_attr_id)->where('value', $value)->pluck('product_sku_id')->toArray();
                    if (!$sku_ids) {
                        break;
                    }
                }
                if (!in_array($product_sku_attr_value, $sku_attr_values[$product_attr_name])) {
                    $sku_attr_values[$product_attr_name][] = $product_sku_attr_value;
                    /*if (count($sku_ids) > 0 && $product_skus->filter(function ($product_sku) {
                            return $product_sku->stock > 0;
                        })->whereIn('id', $sku_ids)->isNotEmpty()
                    ) {*/
                    // if (count($sku_ids) > 0 && $product_skus->where('id', $sku_ids[0])->first()->stock > 0) {
                    if (count($sku_ids) > 0) {
                        $data['data'][$product_attr_name]['true'][] = [
                            'value' => $product_sku_attr_value,
                            'switch' => true,
                        ];
                    } else {
                        $data['data'][$product_attr_name]['false'][] = [
                            'value' => $product_sku_attr_value,
                            'switch' => false,
                        ];
                    }
                }
            });
        }

        // update product_sku_attr_value_cache
        // Note: 当 sku 的 库存 stock 或 属性值 attr—value 发生变动时，要清空对应商品下的所有缓存 cache
        if ($cache_key) {
            $product_sku_attr_value_cache = Cache::get($product->id . 'product_sku_attr_value_cache', []);
            if (!isset($product_sku_attr_value_cache[$cache_key])) {
                $product_sku_attr_value_cache[$cache_key] = $data;
                Cache::forever($product->id . 'product_sku_attr_value_cache', $product_sku_attr_value_cache);
            }
        }

        return response()->json([
            'message' => 'success',
            'data' => $data,
        ], 200);
    }
}
