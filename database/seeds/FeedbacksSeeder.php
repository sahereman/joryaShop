<?php

use App\Models\Feedback;
use Illuminate\Database\Seeder;

class FeedbacksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        factory(Feedback::class, 10)->create();
    }
}
