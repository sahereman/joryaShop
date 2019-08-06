<?php

use App\Models\Attr;
use App\Models\AttrValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttrValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        $attr_values = [
            'Base Size' => [
                '5*7inch',
                '7*10inch',
                '6*8inch',
                '6*9inch',
            ],
            'Hair Color' => [
                '#1 Jet Black',
                '#17 Dark Ash Blonde',
                '#20 Light Ash Blonde',
                '#1720 Dark Ash Blonde With 20%Grey Hair',
                '#18 Medium Blonde',
            ],
            'Hair Density' => [
                '100% Light to Med-light Density',
                '80% Light Density',
                '120% Med-light to Medium Density',
            ]
        ];

        Attr::all()->each(function (Attr $attr) use ($attr_values) {
            foreach ($attr_values[$attr->name] as $key => $attr_value)
            {
                factory(AttrValue::class)->create([
                    'attr_id' => $attr->id,
                    'value' => $attr_value,
                    'sort' => $key + 1
                ]);
            }
        });
    }
}
