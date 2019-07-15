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
            'Base Size',
            'Base Design',
            'Base Material Color',
            'Front Contour',
            'Scallop Front',
            'Hair Length',
            'Curl & Wave',
            'Hair Density',
            'Hair Direction',
            'Hair Color',
            'Grey Hair',
            'Highlight',
            'Bleach Knots',
            'Hair Type',
            'Hair Cut',
            'Production Time'
        ];
        foreach ($custom_attrs as $key => $custom_attr) {
            factory(CustomAttr::class)->create([
                'name' => $custom_attr,
                'sort' => $key + 1
            ]);
        }
    }
}
