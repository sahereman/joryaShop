<?php

use Illuminate\Database\Seeder;

class ShipmentTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $china = \App\Models\CountryProvince::where('name_en', 'China')->first();
        $usa = \App\Models\CountryProvince::where('name_zh', '美国')->first();
        $aus = \App\Models\CountryProvince::where('name_en', 'Australia')->first();

        $template = new \App\Models\ShipmentTemplate([
            'name' => '联邦快递',
            'sub_name' => '中国发货',
            'description' => '全球网络齐全，配送快、东南亚优势明显',
            'from_country_id' => $china->id,
            'min_days' => 3,
            'max_days' => 10,
        ]);

        $template->save();
        $template->free_provinces()->attach(\App\Models\CountryProvince::where('parent_id', $china->id)->get());


        $plan1 = $template->plans()->create([
            'base_unit' => 1,
            'base_price' => 10,
            'join_price' => 5,
        ]);
        $plan1->country_provinces()->attach(\App\Models\CountryProvince::where('parent_id', $usa->id)->get());


        $plan2 = $template->plans()->create([
            'base_unit' => 1,
            'base_price' => 20,
            'join_price' => 10,
        ]);
        $plan2->country_provinces()->attach(\App\Models\CountryProvince::where('parent_id', $aus->id)->get());
    }
}
