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
            ['PC站首页新品 LT 图', 'pc_index_left_top'],
            ['PC站首页新品 LB 图', 'pc_index_left_bottom'],
            ['PC站首页新品 R 图', 'pc_index_right'],
            ['PC站首页楼层 2楼 图', 'pc_index_floor_2'],
            ['PC站首页楼层 4楼 图', 'pc_index_floor_4'],
            ['PC站首页楼层 6楼 图', 'pc_index_floor_6'],
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
