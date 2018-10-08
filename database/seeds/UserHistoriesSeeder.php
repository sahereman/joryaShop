<?php

use Illuminate\Database\Seeder;
use App\Models\UserHistory;
use App\Models\User;
use App\Models\Product;

class UserHistoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        User::all()->each(function (User $user) {

            $products = Product::all()->random(random_int(10, 20));

            foreach ($products as $key => $item)
            {
                factory(UserHistory::class)->create([
                    'user_id' => $user->id,
                    'product_id' => $item->id,
                ]);
            }
        });
    }
}
