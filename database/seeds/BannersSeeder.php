<?php

use Illuminate\Database\Seeder;
use App\Models\Banner;
use Faker\Generator as Faker;


class BannersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run(Faker $faker)
    {
        $prefix_path = Storage::disk('public')->getAdapter()->getPathPrefix();


        factory(Banner::class, 1)->create([
            'type' => 'index',
            'disk' => 'public',
            'image' => $faker->image($prefix_path, 1920, 780, null, false),
        ]);

        factory(Banner::class, 1)->create([
            'type' => 'mobile',
            'disk' => 'public',
            'image' => $faker->image($prefix_path, 960, 390, null, false),
        ]);
    }
}
