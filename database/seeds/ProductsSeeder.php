<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductCategory;

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
        $products = factory(Product::class, 100)->make();
        $products->map(function ($product) use ($categories, $product_names) {
            $category = $categories->random();
            $category_name_zh = $category->parent_id == 0 ? $category->name_zh : $category->parent->name_zh;
            $product->product_category_id = $category->id;
            list($product_name_en, $product_name_zh) = array_random($product_names[$category_name_zh]);
            $product->name_en = $product_name_en;
            $product->name_zh = $product_name_zh;
            Product::create(array_except($product->toArray(), [
                'thumb_url',
                'photo_urls',
                'price_in_usd',
                'shipping_fee_in_usd',
            ]));
        });
    }
}
