<?php

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSlugsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::all()->each(function (Product $product) {
            $product->save();
        });

        ProductCategory::all()->each(function (ProductCategory $productCategory) {
            $productCategory->save();
        });
    }
}
