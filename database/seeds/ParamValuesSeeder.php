<?php

use App\Models\Param;
use App\Models\ParamValue;
use Illuminate\Database\Seeder;

class ParamValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $param_values = [
            'Hue' => [
                'Cool',
                'Warm'
            ],
            'Line' => [
                'Wavy',
                'Curly',
                'Straight'
            ],
            'Style' => [
                'Basic',
                'Casual',
                'Modern',
                'Classic',
                'Natural',
                'Vintage'
            ]
        ];
        Param::all()->each(function (Param $param) use ($param_values) {
            foreach ($param_values[$param->name] as $key => $value) {
                factory(ParamValue::class)->create([
                    'param_id' => $param->id,
                    'value' => $value,
                    'sort' => $key + 1
                ]);
            }
        });
    }
}
