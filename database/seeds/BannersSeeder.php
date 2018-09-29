<?php

use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        //        Banner::truncate();
        //        factory(Banner::class, 10)->create();
        //        $banner = Banner::find(1);
        //        $banner->type = 'index';
        //        $banner->disk = 'local';
        //        $banner->save();


        factory(Banner::class, 1)->create();
    }
}
