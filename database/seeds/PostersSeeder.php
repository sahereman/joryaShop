<?php

use Illuminate\Database\Seeder;
use App\Models\Poster;

class PostersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Poster::truncate();
        factory(Poster::class, 3)->create([
            'slug' => 'poster'
        ]);
        factory(Poster::class, 3)->create([
            'disk' => 'local',
            'slug' => 'advertisement'
        ]);
    }
}
