<?php

use App\Models\Product;
use App\Models\ProductFaq;
use Illuminate\Database\Seeder;

class ProductFaqsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::all()->each(function (Product $product) {
            factory(ProductFaq::class)->create([
                'product_id' => $product->id,
                'question' => 'question content one ???',
                'answer' => 'answer content one',
                'sort' => 3
            ]);
            factory(ProductFaq::class)->create([
                'product_id' => $product->id,
                'question' => 'question content two ???',
                'answer' => 'answer content two',
                'sort' => 2
            ]);
            factory(ProductFaq::class)->create([
                'product_id' => $product->id,
                'question' => 'question content three ???',
                'answer' => 'answer content three',
                'sort' => 1
            ]);
        });
    }
}
