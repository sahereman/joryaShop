<?php

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::truncate();
        factory(Product::class, 10)->create();
        $product = Product::find(1);
        $product->name_en = 'test';
        $product->name_zh = '测试';
        $product->description_en = 'test description';
        $product->description_zh = '测试 描述';
        $product->content_en = 'test content';
        $product->content_zh = '测试 内容';
        $product->save();
    }
}
