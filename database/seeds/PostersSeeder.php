<?php

use Illuminate\Database\Seeder;
use App\Models\Poster;

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
            ['PC站首页新品 图1', 'pc_index_new_1'],
            ['PC站首页新品 图2', 'pc_index_new_2'],
            ['PC站首页新品 图3', 'pc_index_new_3'],
            ['PC站首页楼层 2楼 图1', 'pc_index_2f_1'],
        ];

        foreach ($slug_arr as $item)
        {
            factory(Poster::class)->create([
                'name' => $item[0],
                'slug' => $item[1]
            ]);
        }
    }
}
