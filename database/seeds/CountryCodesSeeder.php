<?php

use Illuminate\Database\Seeder;
use App\Models\CountryCode;

class CountryCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        CountryCode::truncate();

        /*
         * 美国，加拿大，意大利，荷兰，迪拜，
         * 埃及，葡萄牙，西班牙，希腊，印度，
         * 伊朗，以色列，南非，法国，比利时，
         * 俄罗斯，韩国，日本，德国，瑞典，
         * 瑞士
         * */
        /*CountryCode::create([
            'country_name' => 'United States',
            // 'country_name_zh' => '美国',
            'country_iso' => 'US',
            'country_code' => '1',
        ]);

        CountryCode::create([
            'country_name' => 'Canada',
            // 'country_name_zh' => '加拿大',
            'country_iso' => 'CA',
            'country_code' => '1',
        ]);

        CountryCode::create([
            'country_name' => 'Russia',
            // 'country_name_zh' => '俄罗斯',
            'country_iso' => 'RU',
            'country_code' => '7',
        ]);

        CountryCode::create([
            'country_name' => 'Egypt',
            // 'country_name_zh' => '埃及',
            'country_iso' => 'EG',
            'country_code' => '20',
        ]);

        CountryCode::create([
            'country_name' => 'South Africa',
            // 'country_name_zh' => '南非',
            'country_iso' => 'ZA',
            'country_code' => '27',
        ]);

        CountryCode::create([
            'country_name' => 'Greece',
            // 'country_name_zh' => '希腊',
            'country_iso' => 'GR',
            'country_code' => '30',
        ]);

        CountryCode::create([
            'country_name' => 'Netherlands',
            // 'country_name_zh' => '荷兰',
            'country_iso' => 'NL',
            'country_code' => '31',
        ]);

        CountryCode::create([
            'country_name' => 'Belgium',
            // 'country_name_zh' => '比利时',
            'country_iso' => 'BE',
            'country_code' => '32',
        ]);

        CountryCode::create([
            'country_name' => 'France',
            // 'country_name_zh' => '法国',
            'country_iso' => 'FR',
            'country_code' => '33',
        ]);

        CountryCode::create([
            'country_name' => 'Spain',
            // 'country_name_zh' => '西班牙',
            'country_iso' => 'ES',
            'country_code' => '34',
        ]);

        CountryCode::create([
            'country_name' => 'Portugal',
            // 'country_name_zh' => '葡萄牙',
            'country_iso' => 'PT',
            'country_code' => '351',
        ]);

        CountryCode::create([
            'country_name' => 'Italy',
            // 'country_name_zh' => '意大利',
            'country_iso' => 'IT',
            'country_code' => '39',
        ]);

        CountryCode::create([
            'country_name' => 'Switzerland',
            // 'country_name_zh' => '瑞士',
            'country_iso' => 'CH',
            'country_code' => '41',
        ]);*/

        /*CountryCode::create([
            'country_name' => 'United Kingdom',
            // 'country_name_zh' => '英国',
            'country_iso' => 'GB',
            'country_code' => '44',
        ]);*/

        /*CountryCode::create([
            'country_name' => 'Sweden',
            // 'country_name_zh' => '瑞典',
            'country_iso' => 'SE',
            'country_code' => '46',
        ]);

        CountryCode::create([
            'country_name' => 'Germany',
            // 'country_name_zh' => '德国',
            'country_iso' => 'DE',
            'country_code' => '49',
        ]);

        CountryCode::create([
            'country_name' => 'Japan',
            // 'country_name_zh' => '日本',
            'country_iso' => 'JP',
            'country_code' => '81',
        ]);

        CountryCode::create([
            'country_name' => 'South Korea',
            // 'country_name_zh' => '韩国',
            'country_iso' => 'KR',
            'country_code' => '82',
        ]);

        CountryCode::create([
            'country_name' => 'Hong Kong',
            // 'country_name_zh' => '香港',
            'country_iso' => 'HK',
            'country_code' => '852',
        ]);

        CountryCode::create([
            'country_name' => 'Macau',
            // 'country_name_zh' => '澳门',
            'country_iso' => 'MO',
            'country_code' => '853',
        ]);

        CountryCode::create([
            'country_name' => 'China',
            // 'country_name_zh' => '中国',
            'country_iso' => 'CHN',
            'country_code' => '86',
        ]);

        CountryCode::create([
            'country_name' => 'Taiwan',
            // 'country_name_zh' => '台湾',
            'country_iso' => 'TW',
            'country_code' => '886',
        ]);

        CountryCode::create([
            'country_name' => 'India',
            // 'country_name_zh' => '印度',
            'country_iso' => 'IN',
            'country_code' => '91',
        ]);

        // 迪拜[Dubai] 属于 阿拉伯联合酋长国[United Arab Emirates].
        CountryCode::create([
            'country_name' => 'United Arab Emirates',
            // 'country_name_zh' => '阿拉伯联合酋长国',
            'country_iso' => 'AE',
            'country_code' => '971',
        ]);

        CountryCode::create([
            'country_name' => 'Israel',
            // 'country_name_zh' => '以色列',
            'country_iso' => 'IL',
            'country_code' => '972',
        ]);

        CountryCode::create([
            'country_name' => 'Iran',
            // 'country_name_zh' => '伊朗',
            'country_iso' => 'IR',
            'country_code' => '98',
        ]);*/

        // $country_codes = require('../demo/CountryCodes.php');
        $country_codes = require(database_path('demo/CountryCodes.php'));

        foreach ($country_codes as $country_code) {
            // CountryCode::create($country_code);
            factory(CountryCode::class)->create($country_code);
        }
    }
}
