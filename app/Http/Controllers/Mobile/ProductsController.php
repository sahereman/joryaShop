<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\UserFavourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProductsController extends Controller
{
    public function search(ProductRequest $request)
    {
        return view('mobile.products.search', [
            'query' => $request->query('query'),
        ]);
    }

    public function show(Request $request, Product $product)
    {
        // user browsing history - appending (maybe firing an event)
        $productsController = new \App\Http\Controllers\ProductsController();
        $productsController->appendUserBrowsingHistoryCacheByProduct($product);

        $skus = $product->skus;
        $comment_count = $product->comments->count();
        /* SQL: SELECT COUNT(*) FROM `product_comments` WHERE `photos` IS NOT NULL AND `photos` <> ''; */
        $photo_comment_count = $product->comments()->whereNotNull('photos')->Where('photos', '<>', '')->count();
        $user = $request->user();
        $favourite = null;
        if ($user) {
            $favourite = UserFavourite::where('user_id', $user->id)->where('product_id', $product->id)->first();
        }

        if (App::isLocale('en')) {
            $parameters['base_sizes'] = $product->is_base_size_optional ? $skus->unique('base_size_en')->map(function ($item, $key) {
                return $item->base_size_en;
            }) : [];
            $parameters['hair_colours'] = $product->is_hair_colour_optional ? $skus->unique('hair_colour_en')->map(function ($item, $key) {
                return $item->hair_colour_en;
            }) : [];
            $parameters['hair_densities'] = $product->is_hair_density_optional ? $skus->unique('hair_density_en')->map(function ($item, $key) {
                return $item->hair_density_en;
            }) : [];
        } else {
            $parameters['base_sizes'] = $product->is_base_size_optional ? $skus->unique('base_size_zh')->map(function ($item, $key) {
                return $item->base_size_zh;
            }) : [];
            $parameters['hair_colours'] = $product->is_hair_colour_optional ? $skus->unique('hair_colour_zh')->map(function ($item, $key) {
                return $item->hair_colour_zh;
            }) : [];
            $parameters['hair_densities'] = $product->is_hair_density_optional ? $skus->unique('hair_density_zh')->map(function ($item, $key) {
                return $item->hair_density_zh;
            }) : [];
        }

        return view('mobile.products.show', [
            'product' => $product->makeVisible(['content_en', 'content_zh']),
            'skus' => $skus,
            'parameters' => $parameters,
            'comment_count' => $comment_count,
            'photo_comment_count' => $photo_comment_count,
            'favourite' => $favourite,
        ]);
    }
}
