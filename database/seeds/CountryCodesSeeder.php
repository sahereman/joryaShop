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

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Afghanistan',
            'country_name_zh' => '阿富汗',
            'country_iso' => 'AF',
            'country_code' => '93',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Albania',
            'country_name_zh' => '阿尔巴尼亚',
            'country_iso' => 'AL',
            'country_code' => '355',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Algeria',
            'country_name_zh' => '阿尔及利亚',
            'country_iso' => 'DZ',
            'country_code' => '213',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'American Samoa',
            'country_name_zh' => '美属萨摩亚',
            'country_iso' => 'AS',
            'country_code' => '1684',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Andorra',
            'country_name_zh' => '安道尔',
            'country_iso' => 'AD',
            'country_code' => '376',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Angola',
            'country_name_zh' => '安哥拉',
            'country_iso' => 'AO',
            'country_code' => '244',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'China',
            'country_name_zh' => '中国',
            'country_iso' => 'CHN',
            'country_code' => '86',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Hong Kong',
            'country_name_zh' => '香港',
            'country_iso' => 'HK',
            'country_code' => '852',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Macau',
            'country_name_zh' => '澳门',
            'country_iso' => 'MO',
            'country_code' => '853',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Taiwan',
            'country_name_zh' => '台湾',
            'country_iso' => 'TW',
            'country_code' => '886',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Greece',
            'country_name_zh' => '希腊',
            'country_iso' => 'GR',
            'country_code' => '30',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'France',
            'country_name_zh' => '法国',
            'country_iso' => 'FR',
            'country_code' => '33',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Italy',
            'country_name_zh' => '意大利',
            'country_iso' => 'IT',
            'country_code' => '39',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'United Kingdom',
            'country_name_zh' => '英国',
            'country_iso' => 'GB',
            'country_code' => '44',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Germany',
            'country_name_zh' => '德国',
            'country_iso' => 'DE',
            'country_code' => '49',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'United States',
            'country_name_zh' => '美国',
            'country_iso' => 'US',
            'country_code' => '1',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Zambia',
            'country_name_zh' => '赞比亚',
            'country_iso' => 'ZM',
            'country_code' => '260',
        ]);

        factory(CountryCode::class, 1)->create([
            'country_name_en' => 'Zimbabwe',
            'country_name_zh' => '津巴布韦',
            'country_iso' => 'ZW',
            'country_code' => '263',
        ]);
    }
}
