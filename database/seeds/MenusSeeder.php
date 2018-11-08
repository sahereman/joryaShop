<?php

use App\Models\Menu;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class MenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ProductCategory::where('parent_id', 0)
            ->where('is_index', 1)
            ->orderByDesc('sort')
            ->get();
        $categories->each(function (ProductCategory $category) {
            factory(Menu::class)->create([
                'name_en' => $category->name_en,
                'name_zh' => $category->name_zh,
                'slug' => 'pc',
                'link' => route('product_categories.index', ['category' => $category->id]),
            ]);
            factory(Menu::class)->create([
                'name_en' => $category->name_en,
                'name_zh' => $category->name_zh,
                'slug' => 'mobile',
                'link' => route('product_categories.index', ['category' => $category->id]),
            ]);
        });
    }
}
