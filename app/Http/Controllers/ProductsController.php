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
use App\Models\Product;
use App\Models\ProductParam;
use App\Models\ProductSku;
// use App\Models\ProductCategory;
use App\Models\ProductComment;
use App\Models\ProductSkuAttrValue;
use App\Models\ProductSkuCustomAttrValue;
use App\Models\User;
use App\Models\UserFavourite;
use App\Models\UserHistory;
use Illuminate\Http\Request;
// use Illuminate\Support\Carbon;
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
                $param = substr($key, 6);
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
        $attributes = ProductSkuAttrValue::with('sku', 'attr')->whereIn('product_sku_id', $product_sku_ids)->orderByDesc('sort')->get()->map(function (ProductSkuAttrValue $productSkuAttrValue) {
            $attribute = [
                'product_sku_id' => $productSkuAttrValue->product_sku_id,
                'name' => $productSkuAttrValue->name,
                'value' => $productSkuAttrValue->value,
                'stock' => $productSkuAttrValue->sku->stock,
                'price' => $productSkuAttrValue->sku->price,
                'delta_price' => $productSkuAttrValue->sku->delta_price,
            ];
            if ($productSkuAttrValue->attr->has_photo) {
                $attribute['photo_url'] = $productSkuAttrValue->sku->photo_url;
            }
            return $attribute;
        })->groupBy('product_sku_id')->toArray();
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

        // $custom_attrs = CustomAttr::with('values')->orderByDesc('sort')->get();
        /*$custom_attrs->each(function (CustomAttr $customAttr) {
            $customAttr->values = $customAttr->values()->orderByDesc('sort')->get();
        });*/
        // $grouped_custom_attrs = $custom_attrs->groupBy('type');
        $grouped_custom_attrs = CustomAttr::with('values')->orderByDesc('sort')->get()->groupBy('type');
        $custom_attr_types = $grouped_custom_attrs->keys()->toArray();

        return view('products.custom', [
            'product' => $product,
            'custom_attr_types' => $custom_attr_types,
            'grouped_custom_attrs' => $grouped_custom_attrs,
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

        $custom_attr_values = CustomAttrValue::orderByDesc('sort')->whereIn('id', $custom_attr_value_ids)->get();
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

        $custom_attr_values->each(function (CustomAttrValue $customAttrValue) use ($product_sku) {
            ProductSkuCustomAttrValue::create([
                'product_sku_id' => $product_sku->id,
                'name' => $customAttrValue->attr_name,
                'value' => $customAttrValue->value,
                'sort' => $customAttrValue->sort
            ]);
        });

        if ($user) {
            Cart::create([
                'user_id' => $user->id,
                'product_sku_id' => $product_sku->id,
                'number' => 1
            ]);
        } else {
            $carts = session('carts', []);
            // $carts = Session::get('carts', []);
            $flag = false;
            foreach ($carts as $key => $cart) {
                if ($cart['product_sku_id'] == $product_sku->id) {
                    $carts[$key]['number'] += 1;
                    $flag = true;
                    break;
                }
            }
            if (!$flag) {
                $carts[] = [
                    'product_sku_id' => $product_sku->id,
                    'number' => 1
                ];
            }
            session(['carts' => $carts]);
            // Session::put('carts', $carts);
            // Session::put(['carts' => $carts]);
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

        EmailLog::create([
            'email' => $to_email,
            'content' => "Subject: {$subject}. " . view('emails.share', [
                    'product' => $product,
                    'from_email' => $from_email,
                    'body' => $body
                ])
        ]);

        return response()->json([
            'code' => 200,
            'message' => 'success'
        ], 200);
    }
}
