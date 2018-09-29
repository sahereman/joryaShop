<?php

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductSku;

class ProductSkusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {

        Product::all()->each(function (Product $product) {

            factory(ProductSku::class, random_int(2, 4))->create([
                'product_id' => $product->id,
            ]);

        });


        // ProductSku::truncate();
        //        $faker = new Faker();
        //        Product::all()->each(function (Product $product) use ($faker) {
        //            for ($i = 0; $i < 5; $i ++) {
        //                // 现在时间
        //                $now = Carbon::now()->toDateTimeString();
        //                // 随机取一个月以内的时间
        //                $updated_at = $faker->dateTimeThisMonth($now);
        //                // 传参为生成最大时间不超过，创建时间永远比更改时间要早
        //                $created_at = $faker->dateTimeThisMonth($updated_at);
        //                $product_sku_data = [
        //                    'product_id' => $product->id,
        //                    'name_en' => $faker->name,
        //                    'name_zh' => $faker->name,
        //                    'photo' => $faker->imageUrl(),
        //                    'price' => $faker->randomFloat(2, 10, 100),
        //                    'stock' => $faker->randomNumber(3),
        //                    'created_at' => $created_at,
        //                    'updated_at' => $updated_at,
        //                ];
        //                factory(ProductSku::class)->create($product_sku_data);
        //            }
        //        });
    }
}
