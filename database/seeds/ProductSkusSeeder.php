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

            factory(ProductSku::class, random_int(2, 4))->create([
                'product_id' => $product->id,
            ]);
        });
    }
}
