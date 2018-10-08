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
