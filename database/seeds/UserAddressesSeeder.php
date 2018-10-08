<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Product;

class UserAddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        User::all()->each(function (User $user) {

            $products = Product::all()->random(random_int(2, 4));

            foreach ($products as $key => $item)
            {
                factory(UserAddress::class)->create([
                    'user_id' => $user->id,
                ]);
            }

            $address = UserAddress::where('user_id', $user->id)->first();
            $address->is_default = true;
            $address->save();
        });
    }
}
