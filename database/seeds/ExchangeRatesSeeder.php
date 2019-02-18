<?php

use Illuminate\Database\Seeder;
use App\Models\ExchangeRate;

class ExchangeRatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        ExchangeRate::truncate();

        ExchangeRate::create([
            'name' => 'USD to CNY',
            'currency' => 'CNY',
            'rate' => 6.7
        ]);
    }
}
