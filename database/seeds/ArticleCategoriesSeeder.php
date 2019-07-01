<?php

use Illuminate\Database\Seeder;
use App\Models\ArticleCategory;

class ArticleCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $categories = require(database_path('demo/ArticleCategories.php')); // It works.

        foreach ($categories['parents'] as $key => $parent) {
            $parent_model = factory(ArticleCategory::class)->create([
                'parent_id' => 0,
                'name_en' => $parent['name_en'],
                'name_zh' => $parent['name_zh'],
            ]);
        }

    }
}
