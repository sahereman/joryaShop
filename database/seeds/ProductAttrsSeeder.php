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
            $index = 0;
            Attr::all()->each(function (Attr $attr) use ($product, &$index) {
                $index++;
                factory(ProductAttr::class)->create([
                    'product_id' => $product->id,
                    'name' => $attr->name,
                    'has_photo' => $attr->has_photo,
                    'sort' => $attr->sort
                ]);
            });
        });
    }
}
