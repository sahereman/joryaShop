<?php

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        factory(Article::class, 1)->create();
        factory(Article::class, 1)->create([
            'slug' => 'about'
        ]);
    }
}
