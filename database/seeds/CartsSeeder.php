<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cart;
use App\Models\ProductSku;

class CartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cart::truncate();
        User::all()->each(function (User $user) {
            ProductSku::all()->random(5)->each(function (ProductSku $productSku) use ($user) {
                factory(Cart::class)->create(['user_id' => $user->id, 'product_sku_id' => $productSku->id]);
            });
        });
    }
}
