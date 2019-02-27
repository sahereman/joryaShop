<?php

use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Database\Seeder;

class ProductSkusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        Product::all()->each(function (Product $product) {
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'A - 1',
                'name_zh' => 'A - 1 型',

                // 2019-01-22
                'base_size_en' => '8 * 10 (in)',
                'base_size_zh' => '20 * 25 (cm)',
                'hair_colour_en' => 'light grey',
                'hair_colour_zh' => '浅灰色',
                'hair_density_en' => '70%',
                'hair_density_zh' => '70%',
                // 2019-01-22
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'A - 2',
                'name_zh' => 'A - 2 型',

                // 2019-01-22
                'base_size_en' => '10 * 10 (in)',
                'base_size_zh' => '25 * 25 (cm)',
                'hair_colour_en' => 'light grey',
                'hair_colour_zh' => '浅灰色',
                'hair_density_en' => '70%',
                'hair_density_zh' => '70%',
                // 2019-01-22
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'A - 3',
                'name_zh' => 'A - 3 型',

                // 2019-01-22
                'base_size_en' => '10 * 12 (in)',
                'base_size_zh' => '25 * 30 (cm)',
                'hair_colour_en' => 'light grey',
                'hair_colour_zh' => '浅灰色',
                'hair_density_en' => '70%',
                'hair_density_zh' => '70%',
                // 2019-01-22
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'B - 1',
                'name_zh' => 'B - 1 型',

                // 2019-01-22
                'base_size_en' => '10 * 10 (in)',
                'base_size_zh' => '25 * 25 (cm)',
                'hair_colour_en' => 'light grey',
                'hair_colour_zh' => '浅灰色',
                'hair_density_en' => '80%',
                'hair_density_zh' => '80%',
                // 2019-01-22
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'B - 2',
                'name_zh' => 'B - 2 型',

                // 2019-01-22
                'base_size_en' => '10 * 10 (in)',
                'base_size_zh' => '25 * 25 (cm)',
                'hair_colour_en' => 'light brown',
                'hair_colour_zh' => '浅褐色',
                'hair_density_en' => '80%',
                'hair_density_zh' => '80%',
                // 2019-01-22
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'B - 3',
                'name_zh' => 'B - 3 型',

                // 2019-01-22
                'base_size_en' => '10 * 10 (in)',
                'base_size_zh' => '25 * 25 (cm)',
                'hair_colour_en' => 'dark black',
                'hair_colour_zh' => '深黑色',
                'hair_density_en' => '80%',
                'hair_density_zh' => '80%',
                // 2019-01-22
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'C - 1',
                'name_zh' => 'C - 1 型',

                // 2019-01-22
                'base_size_en' => '10 * 12 (in)',
                'base_size_zh' => '25 * 30 (cm)',
                'hair_colour_en' => 'dark black',
                'hair_colour_zh' => '深黑色',
                'hair_density_en' => '70%',
                'hair_density_zh' => '70%',
                // 2019-01-22
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'C - 2',
                'name_zh' => 'C - 2 型',

                // 2019-01-22
                'base_size_en' => '10 * 12 (in)',
                'base_size_zh' => '25 * 30 (cm)',
                'hair_colour_en' => 'dark black',
                'hair_colour_zh' => '深黑色',
                'hair_density_en' => '80%',
                'hair_density_zh' => '80%',
                // 2019-01-22
            ]);
            factory(ProductSku::class)->create([
                'product_id' => $product->id,
                'name_en' => 'C - 3',
                'name_zh' => 'C - 3 型',

                // 2019-01-22
                'base_size_en' => '10 * 12 (in)',
                'base_size_zh' => '25 * 30 (cm)',
                'hair_colour_en' => 'dark black',
                'hair_colour_zh' => '深黑色',
                'hair_density_en' => '90%',
                'hair_density_zh' => '90%',
                // 2019-01-22
            ]);
        });
    }
}
