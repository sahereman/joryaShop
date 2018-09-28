<?php

use Illuminate\Database\Seeder;
use App\Models\ProductSku;

class ProductSkusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductSku::truncate();
        factory(ProductSku::class, 100)->create();
        $productSku = ProductSku::find(1);
        $productSku->name_en = 'test';
        $productSku->name_zh = '测试';
        $productSku->save();
    }
}
