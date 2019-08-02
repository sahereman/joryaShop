<?php

use App\Models\Attr;
use App\Models\AttrValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttrValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attr_values = [
            'Base Size' => [
                1,
                2,
                3,
                4,
                5
            ],
            'Hair Color' => [
                'black',
                'red',
                'blue',
                'brown',
                'white'
            ],
            'Hair Density' => [
                '90%',
                '80%',
                '70%',
                '60%',
                '50%',
            ]
        ];

        Attr::all()->each(function (Attr $attr) use ($attr_values) {
            foreach ($attr_values[$attr->name] as $key => $attr_value) {
                factory(AttrValue::class)->create([
                    'attr_id' => $attr->id,
                    'value' => $attr_value,
                    'sort' => $key + 1
                ]);
            }
        });
    }
}
