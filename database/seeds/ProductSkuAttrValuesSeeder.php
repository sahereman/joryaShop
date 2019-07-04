<?php

use App\Models\ProductAttr;
use App\Models\ProductSku;
use App\Models\ProductSkuAttrValue;
// use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSkuAttrValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $faker = new Faker();
        ProductSku::with('product.attrs')->get()->each(function (ProductSku $productSku) {
            $productSku->product->attrs->each(function (ProductAttr $productAttr) use ($productSku) {
                factory(ProductSkuAttrValue::class)->create([
                    'product_sku_id' => $productSku->id,
                    'product_attr_id' => $productAttr->id,
                    'value' => Str::random(7),
                    'sort' => $productAttr->sort
                ]);
            });
        });
    }
}
