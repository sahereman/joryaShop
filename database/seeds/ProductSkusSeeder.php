<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductSku;

class ProductSkusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        Product::all()->each(function (Product $product) {
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'A 型 - en',
                'name_zh' => 'A 型 - zh',
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'B 型 - en',
                'name_zh' => 'B 型 - zh',
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'C 型 - en',
                'name_zh' => 'C 型 - zh',
            ]);
        });
    }
}
