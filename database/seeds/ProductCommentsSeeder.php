<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductComment;
use Carbon\Carbon;
use Faker\Generator as Faker;

class ProductCommentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {






//        ProductComment::truncate();
//        $faker = new Faker();
//        User::all()->each(function (User $user) use ($faker) {
//            Product::all()->each(function (Product $product) use ($user, $faker) {
//                // 现在时间
//                $now = Carbon::now()->toDateTimeString();
//                // 随机取一个月以内的时间
//                $updated_at = $faker->dateTimeThisMonth($now);
//                // 传参为生成最大时间不超过，创建时间永远比更改时间要早
//                $created_at = $faker->dateTimeThisMonth($updated_at);
//                return [
//                    'parent_id' => 0,
//                    'user_id' => $user->id,
//                    'order_id' => $faker->randomNumber(2),
//                    'product_id' => $product->id,
//                    'content' => $faker->text(50),
//                    'photos' => json_encode(array($faker->imageUrl(), $faker->imageUrl(), $faker->imageUrl())),
//                    'created_at' => $created_at,
//                ];
//            });
//        });
    }
}
