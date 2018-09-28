<?php

use Illuminate\Database\Seeder;
use App\Models\ProductComment;

class ProductCommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductComment::truncate();
        factory(ProductComment::class, 100)->create();
        $productComment = ProductComment::find(1);
        $productComment->content = '测试 内容';
        $productComment->save();
    }
}
