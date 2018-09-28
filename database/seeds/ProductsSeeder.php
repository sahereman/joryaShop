<?php

use Illuminate\Database\Seeder;
use App\Models\ProductCategory;
use App\Models\Product;
use Carbon\Carbon;
use Faker\Generator as Faker;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Product::truncate();
        $faker = new Faker();
        ProductCategory::all()->each(function (ProductCategory $productCategory) use ($faker) {
            for ($i = 0; $i < 5; $i ++) {
                // 现在时间
                $now = Carbon::now()->toDateTimeString();
                // 随机取一个月以内的时间
                $updated_at = $faker->dateTimeThisMonth($now);
                // 传参为生成最大时间不超过，创建时间永远比更改时间要早
                $created_at = $faker->dateTimeThisMonth($updated_at);
                $product_data = [
                    'product_category_id' => $productCategory->id,
                    'name_en' => $faker->name,
                    'name_zh' => $faker->name,
                    'description_en' => $faker->sentence(10),
                    'description_zh' => $faker->sentence(10),
                    'content_en' => $faker->text(100),
                    'content_zh' => $faker->text(100),
                    'photos' => json_encode(array($faker->imageUrl(), $faker->imageUrl(), $faker->imageUrl())),
                    'shipping_fee' => $faker->randomFloat(2, 10, 20),
                    'stock' => $faker->randomNumber(5),
                    'on_sale' => true,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];
                factory(Product::class)->create($product_data);
            }
        });
    }
}
