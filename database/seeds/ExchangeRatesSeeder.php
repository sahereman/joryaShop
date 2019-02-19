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

        /*汇率基准: 美元(USD) $1.00*/
        // 澳元
        ExchangeRate::create([
            'name' => 'USD to AUD',
            'currency' => 'AUD',
            'rate' => 1.40,
        ]);

        // 加元
        ExchangeRate::create([
            'name' => 'USD to CAD',
            'currency' => 'CAD',
            'rate' => 1.32,
        ]);

        // 人民币
        ExchangeRate::create([
            'name' => 'USD to CNY',
            'currency' => 'CNY',
            'rate' => 6.76,
        ]);

        // 欧元
        ExchangeRate::create([
            'name' => 'USD to EUR',
            'currency' => 'EUR',
            'rate' => 0.88,
        ]);

        // 英镑
        ExchangeRate::create([
            'name' => 'USD to GBP',
            'currency' => 'GBP',
            'rate' => 0.77,
        ]);

        // 港元
        ExchangeRate::create([
            'name' => 'USD to HKD',
            'currency' => 'HKD',
            'rate' => 7.85,
        ]);

        // 日元
        ExchangeRate::create([
            'name' => 'USD to JPY',
            'currency' => 'JPY',
            'rate' => 110.52,
        ]);

        // 韩元
        ExchangeRate::create([
            'name' => 'USD to KRW',
            'currency' => 'KRW',
            'rate' => 1126.83,
        ]);

        // 台币
        ExchangeRate::create([
            'name' => 'USD to TWD',
            'currency' => 'TWD',
            'rate' => 30.83,
        ]);
    }
}
