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
        $slug_arr = [
            // 关于我们
            ['关于我们', 'about'],
            ['公司简介', 'company_introduction'],
            ['产品特色', 'products_features'],
            ['联系我们', 'contact_us'],
            // 使用帮助
            ['使用帮助', 'helper'],
            ['新手指南', 'guide'],
            ['常见问题', 'problem'],
            ['用户协议', 'user_protocol'],
            // 售后服务
            ['售后服务', 'refunding_service'],
            ['售后咨询', 'refunding_consultancy'],
            ['退货政策', 'refunding_policy'],
            ['退货办理', 'refunding_procedure'],
            // PC: Right-Top 4 tabs
            ['Stock Order', 'stock_order'],
            ['Custom Order', 'custom_order'],
            ['Duplicate', 'duplicate'],
            ['Repair', 'repair'],
        ];

        foreach ($slug_arr as $item)
        {
            factory(Article::class)->create([
                'name' => $item[0],
                'slug' => $item[1]
            ]);
        }
    }
}
