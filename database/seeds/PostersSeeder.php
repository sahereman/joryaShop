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
        factory(Poster::class, 10)->create();
        $poster = Poster::find(1);
        $poster->disk = 'local';
        $poster->save();
    }
}
