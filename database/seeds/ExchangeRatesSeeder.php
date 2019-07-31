<?php

use App\Models\ExchangeRate;
use Illuminate\Database\Seeder;

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
            'name' => 'Australian Dollar',
            'currency' => 'AUD',
            'rate' => 1.40,
        ]);

        // 英镑
        ExchangeRate::create([
            'name' => 'British Pound Sterling',
            'currency' => 'GBP',
            'rate' => 0.77,
        ]);

        // 加元
        ExchangeRate::create([
            'name' => 'Canadian Dollar',
            'currency' => 'CAD',
            'rate' => 1.32,
        ]);

        // 欧元
        ExchangeRate::create([
            'name' => 'Euro',
            'currency' => 'EUR',
            'rate' => 0.88,
        ]);

        // 卢布
        ExchangeRate::create([
            'name' => 'Russian Ruble',
            'currency' => 'RUB',
            'rate' => 62.75,
        ]);

        // 人民币
        /*ExchangeRate::create([
            'name' => 'USD to CNY',
            'currency' => 'CNY',
            'rate' => 6.76,
        ]);*/

        // 港元
        /*ExchangeRate::create([
            'name' => 'USD to HKD',
            'currency' => 'HKD',
            'rate' => 7.85,
        ]);*/

        // 日元
        /*ExchangeRate::create([
            'name' => 'USD to JPY',
            'currency' => 'JPY',
            'rate' => 110.52,
        ]);*/

        // 韩元
        /*ExchangeRate::create([
            'name' => 'USD to KRW',
            'currency' => 'KRW',
            'rate' => 1126.83,
        ]);*/

        // 台币
        /*ExchangeRate::create([
            'name' => 'USD to TWD',
            'currency' => 'TWD',
            'rate' => 30.83,
        ]);*/
    }
}
