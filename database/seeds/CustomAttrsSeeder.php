<?php

use App\Models\CustomAttr;
use Illuminate\Database\Seeder;

class CustomAttrsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $custom_attrs = [
            'BASE' => [
                'Base Size',
                'Base Design',
                'Base Material Color',
                'Front Contour',
                'Scallop Front'
            ],
            'HAIR' => [
                'Hair Length',
                'Curl & Wave',
                'Hair Density',
                'Hair Direction'
            ],
            'OTHERS' => [
                'Hair Color',
                'Grey Hair',
                'Highlight',
                'Bleach Knots',
                'Hair Type'
            ],
            'SERVICE' => [
                'Hair Cut',
                'Production Time'
            ]
        ];
        $i = 0;
        foreach ($custom_attrs as $type => $attrs) {
            foreach ($attrs as $key => $attr) {
                $i++;
                factory(CustomAttr::class)->create([
                    'type' => $type,
                    'name' => $attr,
                    'sort' => $i + 1
                ]);
            }
        }
    }
}
