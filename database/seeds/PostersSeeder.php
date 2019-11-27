<?php

use App\Models\Poster;
use Illuminate\Database\Seeder;

class PostersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        // Poster::truncate();
        $slug_arr = [
            /*PC*/
            // ['About LyricalHair - Up', 'about_lyricalhair_up'],
            // ['About LyricalHair - Down', 'about_lyricalhair_down'],
            // ['About LyricalHair - Left', 'about_lyricalhair_left'],
            // ['About LyricalHair - Right', 'about_lyricalhair_right'],
            ["Men's Wig", 'mens_wig'],
            ["Ladies' Wig", 'ladies_wig'],
            ["Wig Accessories", 'wig_accessories'],
            ['Why LyricalHair', 'why_lyricalhair'],
            ['About Us', 'about_us'],
        ];

        foreach ($slug_arr as $item) {
            factory(Poster::class)->create([
                'name' => $item[0],
                'slug' => $item[1],
                'description' => $item[0],
            ]);
        }
    }
}
