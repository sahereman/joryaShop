<?php

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ProductCategory::truncate();
        factory(ProductCategory::class, 10)->create();
        $productCategory = ProductCategory::find(1);
        $productCategory->parent_id = 0;
        $productCategory->name_en = 'test';
        $productCategory->name_zh = '测试';
        $productCategory->description_en = 'test description';
        $productCategory->description_zh = '测试 描述';
        $productCategory->save();
    }
}
