<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserFavourite;
use App\Models\Product;

class UserFavouritesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        // UserFavourite::truncate();
        //        factory(UserFavourite::class, 10)->create();
        //        $userFavourite = UserFavourite::find(1);
        //        $userFavourite->product_id = 1;
        //        $userFavourite->save();


        User::all()->each(function (User $user) {

            $products = Product::all()->random(random_int(3, 6));

            foreach ($products as $key => $item)
            {
                factory(UserFavourite::class)->create([
                    'user_id' => $user->id,
                    'product_id' => $item->id,
                ]);
            }
        });
    }
}
