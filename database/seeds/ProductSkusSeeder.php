<?php

use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Database\Seeder;

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
                'name_en' => 'A - 1',
                'name_zh' => 'A - 1 型',
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'A - 2',
                'name_zh' => 'A - 2 型',
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'A - 3',
                'name_zh' => 'A - 3 型',
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'B - 1',
                'name_zh' => 'B - 1 型',
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'B - 2',
                'name_zh' => 'B - 2 型',
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'B - 3',
                'name_zh' => 'B - 3 型',
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'C - 1',
                'name_zh' => 'C - 1 型',
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'C - 2',
                'name_zh' => 'C - 2 型',
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'C - 3',
                'name_zh' => 'C - 3 型',
            ]);
        });
    }
}
