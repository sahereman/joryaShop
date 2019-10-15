<?php

use App\Admin\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductLocation;
use App\Models\ProductService;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {

        //生成100个产品,随机分配分类

        /*$category_ids = ProductCategory::all()->pluck('id')->toArray();

        $products = factory(Product::class, 100)->make();

        $products->map(function ($item) use ($category_ids) {

            $item->product_category_id = array_random($category_ids);

            Product::create(array_except($item->toArray(), [
                'thumb_url',
                'photo_urls',
                'price_in_usd',
                'shipping_fee_in_usd',
            ]));
        });*/

        // $product_names = require('../demo/Products.php');
        $product_names = require(database_path('demo/Products.php'));

        $categories = ProductCategory::all();
        $product_locations = ProductLocation::all();
        $product_services = ProductService::all();
        $products = factory(Product::class, 100)->make();
        $products->map(function ($product) use ($categories, $product_names, $product_locations, $product_services) {
            $category = $categories->random();
            $category_name_zh = $category->parent_id == 0 ? $category->name_zh : $category->parent->name_zh;
            $product->product_category_id = $category->id;
            list($product_name_en, $product_name_zh) = array_random($product_names[$category_name_zh]);
            $product->name_en = $product_name_en;
            $product->name_zh = $product_name_zh;
            $product->seo_title = $product_name_en;
            $product->seo_keywords = $product_name_en . ', ' . $product_name_zh;
            $product->seo_description = $product_name_en . ', ' . $product_name_zh . ', ' . $product->description_en;
            $product->location = $product_locations->random()->description;
            $product->service = $product_services->random()->description;
            /*Product::create(array_except($product->toArray(), [
                //
            ]));*/
            Product::create($product->toArray());
        });
        Product::first()->update(['type' => 'custom']);
    }
}
