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
            ['About LyricalHair - Up', 'about_lyrical_hair_up'],
            ['About LyricalHair - Down', 'about_lyrical_hair_down'],
            ['About LyricalHair - Left', 'about_lyrical_hair_left'],
            ['About LyricalHair - Right', 'about_lyrical_hair_right'],
            ['Why LyricalHair - 1', 'why_lyrical_hair_1'],
            ['Why LyricalHair - 2', 'why_lyrical_hair_2'],
        ];

        foreach ($slug_arr as $item) {
            factory(Poster::class)->create([
                'name' => $item[0],
                'slug' => $item[1],
            ]);
        }
    }
}
