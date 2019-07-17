<?php

use Illuminate\Database\Seeder;

class DistributionLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\DistributionLevel::create([
            'level' => 1,
            'profit_ratio' => 0.10,
        ]);

        \App\Models\DistributionLevel::create([
            'level' => 2,
            'profit_ratio' => 0.05,
        ]);
    }
}
