<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cart;
use App\Models\ProductSku;

class CartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        User::all()->each(function (User $user) {
            $skus = ProductSku::all()->random(random_int(3, 6));
            foreach ($skus as $key => $item) {
                $cart = factory(Cart::class)->make([
                    'user_id' => $user->id,
                    'product_sku_id' => $item->id,
                ]);
                Cart::create(array_except($cart->toArray(), [
                    'favourite',
                ]));
            }
        });
    }
}
