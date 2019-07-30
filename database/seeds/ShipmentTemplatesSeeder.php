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
        $usa = \App\Models\CountryProvince::where('name_en', 'United States')->first();
        $aus = \App\Models\CountryProvince::where('name_en', 'Australia')->first();

        $other_provinces = \App\Models\CountryProvince::whereNotIn('parent_id', [$china->id, $usa->id, $aus->id])->where('type', 'province')->get();

        // 模板 1
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
            'base_price' => 4,
            'join_price' => 2,
        ]);
        $plan1->country_provinces()->attach(\App\Models\CountryProvince::where('parent_id', $usa->id)->get());


        $plan2 = $template->plans()->create([
            'base_unit' => 1,
            'base_price' => 8,
            'join_price' => 4,
        ]);
        $plan2->country_provinces()->attach(\App\Models\CountryProvince::where('parent_id', $aus->id)->get());


        $plan3 = $template->plans()->create([
            'base_unit' => 1,
            'base_price' => 10,
            'join_price' => 5,
        ]);
        $plan3->country_provinces()->attach($other_provinces);


        // 模板 2
        $template = new \App\Models\ShipmentTemplate([
            'name' => 'USPS美国邮政',
            'sub_name' => '美国发货',
            'description' => '邮政上门、揽收配送快、价格较高',
            'from_country_id' => $usa->id,
            'min_days' => 2,
            'max_days' => 7,
        ]);
        $template->save();

        $template->free_provinces()->attach(\App\Models\CountryProvince::where('parent_id', $usa->id)->get());


        $plan1 = $template->plans()->create([
            'base_unit' => 1,
            'base_price' => 4,
            'join_price' => 2,
        ]);
        $plan1->country_provinces()->attach(\App\Models\CountryProvince::where('parent_id', $aus->id)->get());


        $plan2 = $template->plans()->create([
            'base_unit' => 1,
            'base_price' => 8,
            'join_price' => 4,
        ]);
        $plan2->country_provinces()->attach(\App\Models\CountryProvince::where('parent_id', $china->id)->get());


        $plan3 = $template->plans()->create([
            'base_unit' => 1,
            'base_price' => 10,
            'join_price' => 5,
        ]);
        $plan3->country_provinces()->attach($other_provinces);
    }
}
