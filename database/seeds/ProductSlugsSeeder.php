<?php

use App\Models\Product;
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
    }
}
