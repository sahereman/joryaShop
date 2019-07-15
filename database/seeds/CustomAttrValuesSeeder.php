<?php

use App\Models\CustomAttr;
use App\Models\CustomAttrValue;
use Illuminate\Database\Seeder;

class CustomAttrValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $custom_attr_values = [
            'Base Size' => [
                ['size < 4"x4", or area < 16 square inches', 11],
                ['4"x4"≤ size ≤8"x10", or 16 square inches≤ area ≤80 square inches', 12],
                ['8"x10"< size <10"x10", or 80 square inches< area <100 square inches', 13],
                ['size ≥ 10"x10", or area ≥ 100 square inches', 14]
            ],
            'Base Design' => [
                ['L15 French lace with PU edge on sides & back', 11],
                ['L16 Fine welded mono with PU edge on sides & back', 12],
                ['S1 Thin skin all over', 13],
                ['S12 Thin skin with mono lace front( zig zag connection)', 14],
                ['S13 French lace with PU perimeter', 15],
                ['S15 Thin skin with 1/4" French lace front', 16],
                ['S16 Integration base with skin perimeter', 11],
                ['S2 Fine welded mono lace all over', 12],
                ['S3 Fine mono with PU perimeter', 13],
                ['S4 Super fine mono with PU perimeter and 1/4" lace front', 14],
                ['S7 French lace all over', 15]
            ],
            'Base Material Color' => [
                ['Flesh', 11],
                ['Light Brown', 12],
                ['Brown', 13],
                ['Black', 14]
            ],
            'Front Contour' => [
                ['AA (V peak shape)', 11],
                ['A (between AA and CC)', 12],
                ['CC (round shape)', 13],
                ['C(nearly straight shape)', 14]
            ],
            'Scallop Front' => [
                ['#1 scallop size', 11],
                ['#2 scallop size', 12],
                ['#3 scallop size', 13],
                ['#4 scallop size', 14],
                ['#5 scallop size', 15],
                ['#6 scallop size', 16]
            ],
            'Hair Length' => [
                ['6 Inch', 11],
                ['7 Inch', 12],
                ['8 Inch', 13],
                ['9 Inch', 14],
                ['10 Inch', 15],
                ['11 Inch', 16]
            ],
            'Curl & Wave' => [
                ['straight', 11],
                ['36mm body wave', 12],
                ['32mm slight wave', 13],
                ['25mm medium wave', 14],
                ['19mm tight wave', 15],
                ['15mm loose curl', 16],
                ['10mm tight curl', 11],
                ['4mm medium Afro', 13]
            ],
            'Hair Density' => [
                ['Extra light', 11],
                ['Light', 12],
                ['Medium light', 13],
                ['Medium', 14],
                ['Medium heavy', 15],
                ['Heavy', 16]
            ],
            'Hair Direction' => [
                ['Free style', 11],
                ['Left parting', 12],
                ['Right parting', 13],
                ['Center parting', 14],
                ['Left break', 15],
                ['Right break', 16],
                ['Left crown', 11],
                ['Right crown', 12],
                ['Center crown', 13],
                ['Brush back', 14]
            ],
            'Hair Color' => [
                ['1#', 11],
                ['1A#', 12],
                ['2#', 13],
                ['3#', 14],
                ['4#', 15],
                ['5#', 16]
            ],
            'Grey Hair' => [
                ['No need grey hair', 11],
                ['I want grey hair - Human grey hair', 12],
                ['I want grey hair - Synthetic grey hair (best choice)', 13],
                ['I want grey hair - Yak (similar to human but thicker and shinny)', 14]
            ],
            'Highlight' => [
                ['No need highlights', 11],
                ['No need highlights - Same as the old unit I′ll send in', 12]
            ],
            'Bleach Knots' => [
                ['No need bleach knots', 11],
                ['Bleach knots 1/2" in distance from front', 12],
                ['Bleach knots 1" in distance from front', 13],
                ['Bleach knots all over', 14],
                ['Bleach knots on parting', 15],
                ['Bleach knots on crown', 16]
            ],
            'Hair Type' => [
                ['Remy hair (best)', 11],
                ['Indian human hair (medium thickness)', 12],
                ['European hair (fine, thin & soft, 7" and up is not available)', 13],
                ['Chinese hair (coarse, good for extremely straight)', 14],
                ['Synthetic hair', 15]
            ],
            'Hair Cut' => [
                ['No,I will have my hair cut-in and styled by my stylist', 11],
                ['Yes,have hair cut-in and styled - LD004', 12],
                ['Yes,have hair cut-in and styled - LD005', 13],
                ['Yes,have hair cut-in and styled - LD006', 14],
                ['Yes,have hair cut-in and styled - LD007', 15],
                ['Yes,have hair cut-in and styled - LD008', 16]
            ],
            'Production Time' => [
                ['Average service 7.5-8.5 weeks', 11],
                ['Rush service 5.5-6.5 weeks', 12]
            ]
        ];
        CustomAttr::all()->each(function (CustomAttr $customAttr) use ($custom_attr_values) {
            foreach ($custom_attr_values[$customAttr->name] as $key => $custom_attr_value) {
                factory(CustomAttrValue::class)->create([
                    'custom_attr_id' => $customAttr->id,
                    'value' => $custom_attr_value[0],
                    'delta_price' => $custom_attr_value[1],
                    'sort' => $key + $customAttr->sort
                ]);
            }
        });
    }
}
