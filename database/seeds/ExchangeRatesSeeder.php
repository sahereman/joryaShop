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
            'name' => '人民币兑换美元汇率',
            'currency' => 'USD',
            'rate' => 0.15
        ]);
    }
}
