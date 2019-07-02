<?php

use App\Models\ParamValue;
use App\Models\Product;
use App\Models\ProductParam;
use Illuminate\Database\Seeder;

class ProductParamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::all()->each(function (Product $product) {
            ParamValue::all()->random(3)->each(function (ParamValue $value) use ($product) {
                factory(ProductParam::class)->create([
                    'product_id' => $product->id,
                    'name' => $value->param->name,
                    'value' => $value->value,
                    'sort' => $value->sort
                ]);
            });
        });
    }
}
