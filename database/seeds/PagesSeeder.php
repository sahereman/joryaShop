<?php

use Illuminate\Database\Seeder;
use App\Models\Page;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        factory(Page::class, 1)->create();
        factory(Page::class, 1)->create([
            'slug' => 'about'
        ]);
    }
}
