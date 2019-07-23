<?php

use Illuminate\Database\Seeder;
use App\Models\CountryProvince;

class CountryProvincesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $xml = simplexml_load_file(database_path('demo/LocList_en.xml'));

        foreach ($xml as $item)
        {
            if(count($item->State) == 0)
            {
                continue;
            }
            $country = CountryProvince::create([
                'parent_id' => 0,
                'type' => 'country',
                'name_en' => $item['Name'],
                'code' => $item['Code'],
            ]);

            foreach ($item->State as $state)
            {

                if ($state['Name'] == null)
                {
                    $province = CountryProvince::create([
                        'parent_id' => $country->id,
                        'type' => 'province',
                        'name_en' => $item['Name'],
                        'code' => $country->code . $item['Code'],
                    ]);

                    foreach ($state->City as $city)
                    {
                        CountryProvince::create([
                            'parent_id' => $province->id,
                            'type' => 'city',
                            'name_en' => $city['Name'],
                            'code' => $province->code . $city['Code'],
                        ]);
                    }
                    break;
                } else
                {
                    $province = CountryProvince::create([
                        'parent_id' => $country->id,
                        'type' => 'province',
                        'name_en' => $state['Name'],
                        'code' => $country->code . $state['Code'],
                    ]);

                    foreach ($state->City as $city)
                    {
                        CountryProvince::create([
                            'parent_id' => $province->id,
                            'type' => 'city',
                            'name_en' => $city['Name'],
                            'code' => $province->code . $city['Code'],
                        ]);
                    }
                }

            }
        }


        $xml2 = simplexml_load_file(database_path('demo/LocList_zh.xml'));

        foreach ($xml2 as $item)
        {
            if(count($item->State) == 0)
            {
                continue;
            }
            $country = CountryProvince::where('parent_id', 0)->where('code', $item['Code'])->first();
            $country->name_zh = $item['Name'];
            $country->save();

            foreach ($item->State as $state)
            {

                if ($state['Name'] == null)
                {
                    $province = CountryProvince::where('parent_id', $country->id)->where('code', $country->code . $item['Code'])->first();
                    $province->name_zh = $item['Name'];
                    $province->save();

                    foreach ($state->City as $city)
                    {
                        CountryProvince::where('parent_id', $province->id)->where('code', $province->code . $city['Code'])->update([
                            'name_zh' => $city['Name']
                        ]);
                    }

                    break;
                } else
                {
                    $province = CountryProvince::where('parent_id', $country->id)->where('code', $country->code . $state['Code'])->first();
                    $province->name_zh = $state['Name'];
                    $province->save();

                    foreach ($state->City as $city)
                    {
                        CountryProvince::where('parent_id', $province->id)->where('code', $province->code . $city['Code'])->update([
                            'name_zh' => $city['Name']
                        ]);
                    }
                }

            }
        }
    }
}
