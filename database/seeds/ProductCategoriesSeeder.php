<?php

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        // ProductCategory::truncate();
        //        factory(ProductCategory::class, 10)->create();
        //        $productCategory = ProductCategory::find(1);
        //        $productCategory->parent_id = 0;
        //        $productCategory->name_en = 'test';
        //        $productCategory->name_zh = '测试';
        //        $productCategory->description_en = 'test description';
        //        $productCategory->description_zh = '测试 描述';
        //        $productCategory->save();


        //一级
        factory(ProductCategory::class, 5)->create(
            ['parent_id' => 0]
        );


        //二级
        ProductCategory::all()->each(function (ProductCategory $category) {

            factory(ProductCategory::class, random_int(1, 5))->create(
                ['parent_id' => $category->id]
            );
        });


    }
}
