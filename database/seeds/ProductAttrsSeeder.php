<?php

use App\Models\Attr;
use App\Models\Product;
use App\Models\ProductAttr;
use Illuminate\Database\Seeder;

class ProductAttrsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::all()->each(function (Product $product) {
            Attr::all()->each(function (Attr $attr) use ($product) {
                factory(ProductAttr::class)->create([
                    'product_id' => $product->id,
                    'name' => $attr->name,
                    'sort' => $attr->sort
                ]);
            });
        });
    }
}
