<?php

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $slug_arr = [
            // COMPANY INFO
            ['About Us', 'about_us'],
            ['Contact Us', 'contact_us'],
            ['Privacy Policy', 'privacy_policy'],
            ['Terms and Conditions', 'terms_and_conditions'],
            ['Sitemap', 'sitemap'],
            // HELP & SUPPORT
            ['Newbie Guide', 'newbie_guide'],
            ['FAQs', 'FAQs'],
            ['Payment Methods', 'payment_methods'],
            ['Shipping & Handling', 'shipping_&_handling'],
            ['Warranty and Return', 'warranty_and_return'],
            ['Track My Order', 'track_my_order'],
            // BE OUR PARTNER
            ['Wholesale', 'wholesale'],
            ['Drop Shipment', 'drop_shipment'],
            ['News', 'news'],
            ['Videos', 'videos'],
            // CUSTOM OPTIONS
            ['Base Design Guide', 'base_design_guide'],
            ['Base Size', 'base_size'],
            ['Hair Style', 'hair_style'],
            ['Hair Texture', 'hair_texture'],
            ['Hair Density', 'hair_density'],
            ['Hair Type', 'hair_type'],
            ['Front Contour', 'front_contour'],
            ['Scallop Front', 'scallop_front'],
            // PRODUCT HELP
            ['Currency Rates', 'currency_rates'],
            ['How to Make Template', 'how_to_make_template'],
            ['How to Clean Toupee', 'how_to_clean_toupee'],
            ['How to Comb the Hair', 'how_to_comb_the_hair'],
            ['How to Match Length', 'how_to_match_length'],
            // PC: Right-Top 4 tabs
            ['Stock Order', 'stock_order'],
            ['Custom Order', 'custom_order'],
            ['Duplicate', 'duplicate'],
            ['Repair', 'repair'],
            // Home > My Account > Service Center
            ['After Sales Service', 'refunding_service'],
            ['Why Lyricalhair', 'why_lyricalhair'],
        ];

        $category = \App\Models\ArticleCategory::first();

        foreach ($slug_arr as $item)
        {
            factory(Article::class)->create([
                'category_id' => $category->id,
                'name' => $item[0],
                'slug' => $item[1],
            ]);
        }
    }
}
