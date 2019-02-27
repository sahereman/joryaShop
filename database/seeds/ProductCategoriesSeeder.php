<?php

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

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

        foreach ($product_categories['parents'] as $key => $parent) {
            $parent_model = factory(ProductCategory::class)->create([
                'parent_id' => 0,
                'is_index' => $parent['is_index'],
                'name_en' => $parent['name_en'],
                'name_zh' => $parent['name_zh'],
            ]);
            foreach ($product_categories['children'][$key] as $child_name_en => $child_name_zh) {
                factory(ProductCategory::class)->create([
                    'parent_id' => $parent_model->id,
                    'is_index' => 0,
                    'name_en' => $child_name_en,
                    'name_zh' => $child_name_zh,
                ]);
            }
        }
    }
}
