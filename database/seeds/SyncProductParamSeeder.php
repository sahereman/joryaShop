<?php

use App\Models\ParamValue;
use App\Models\ProductParam;
use Illuminate\Database\Seeder;

class SyncProductParamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductParam::all()->each(function (ProductParam $productParam) {
            $param_value = ParamValue::with('param')->where('value', $productParam->value)->first();
            if ($param_value) {
                // $productParam->param_value()->associate($param_value);
                // $productParam->param_value_id = $param_value->id;
                // $productParam->save();
                // $productParam->param_value()->associate($param_value->id);
                // $productParam->param_value_id = $param_value->id;
                // $productParam->name = $param_value->param->name;
                // $productParam->save();
                $productParam->update(['param_value_id' => $param_value->id]);
            } else {
                $productParam->delete();
            }
        });
    }
}
