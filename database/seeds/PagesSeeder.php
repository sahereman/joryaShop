<?php

use Illuminate\Database\Seeder;
use App\Models\Page;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::truncate();
        factory(Page::class, 10)->create();
        $page = Page::find(1);
        $page->content_en = 'test content';
        $page->content_zh = '测试 内容';
        $page->save();
    }
}
