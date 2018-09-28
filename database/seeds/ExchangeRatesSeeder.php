<?php

use Illuminate\Database\Seeder;
use App\Models\ExchangeRate;

class ExchangeRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExchangeRate::truncate();
        factory(ExchangeRate::class, 10)->create();
    }
}
