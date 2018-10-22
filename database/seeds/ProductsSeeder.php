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

        $category_ids = ProductCategory::all()->pluck('id')->toArray();

        $products = factory(Product::class, 100)->make();

        $products->map(function ($item) use ($category_ids) {

            $item->product_category_id = array_random($category_ids);
            $item->save();

        });
    }
}
