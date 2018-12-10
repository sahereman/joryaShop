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
        //一级
        /*factory(ProductCategory::class, 5)->create([
            'parent_id' => 0,
            'is_index' => true,
        ]);*/

        //二级
        /*ProductCategory::all()->each(function (ProductCategory $category) {

            factory(ProductCategory::class, random_int(1, 5))->create(
                ['parent_id' => $category->id]
            );
        });*/

        // $product_categories = require('../demo/ProductCategories.php'); // It doesn't work.
        $product_categories = require(database_path('demo/ProductCategories.php')); // It works.

        foreach ($product_categories as $parent => $children) {
            $parent_category = factory(ProductCategory::class)->create([
                'parent_id' => 0,
                'is_index' => 1,
                'name_en' => $parent . ' - en',
                'name_zh' => $parent . ' - zh',
            ]);
            foreach ($children as $child) {
                factory(ProductCategory::class)->create([
                    'parent_id' => $parent_category->id,
                    'is_index' => 1,
                    'name_en' => $child . ' - en',
                    'name_zh' => $child . ' - zh',
                ]);
            }
        }
    }
}
