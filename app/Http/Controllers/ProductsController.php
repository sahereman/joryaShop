<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductSku;
use App\Models\ProductComment;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    // GET 搜素结果 [下拉加载更多]
    public function search(Request $request)
    {
        $this->validate($request, [
            'query' => 'required|string|min:2',
            'page' => 'sometimes|required|integer|min:1',
        ], [], [
            'query' => '搜索内容',
            'page' => '页码',
        ]);
        $query = $request->query('query');
        $current_page = $request->has('page') ? $request->input('page') : 1;
        // on_sale: 是否在售 + index: 综合指数
        $products = Product::where('on_sale', 1)
            ->where('name_en', 'like', '%' . $query . '%')
            ->orWhere('name_en', 'like', '%' . $query . '%')
            ->orWhere('description_en', 'like', '%' . $query . '%')
            ->orWhere('description_zh', 'like', '%' . $query . '%')
            ->orWhere('content_en', 'like', '%' . $query . '%')
            ->orWhere('content_zh', 'like', '%' . $query . '%')
            ->orderByDesc('index')
            ->simplePaginate(10);
        $product_count = $products->count();
        $page_count = ceil($product_count / 5);
        $next_page = ($current_page < $page_count) ? ($current_page + 1) : false;
        if ($next_page == false) {
            $request_url = false;
        } else {
            $request_url = route('products.search') . '?query=' . $query . '&page=' . $next_page;
        }
        return view('products.index', [
            'products' => $products,
            'request_url' => $request_url,
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
            ->orWhere('name_en', 'like', '%' . $query . '%')
            ->orderByDesc('index')
            ->limit(10)
            ->get();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'products' => $products,
        ]);
    }

    // 商品详情
    public function show(Request $request, Product $product)
    {
        if ($product->on_sale == 0) {
            throw new InvalidRequestException('该商品尚未上架');
        }
        $category = $product->category()->with('parent')->get();
        $skus = $product->skus;
        $comments = $product->comments;
        return view('products.show', [
            'category' => $category,
            'product' => $product,
            'skus' => $skus,
            'comments' => $comments,
        ]);
    }
}
