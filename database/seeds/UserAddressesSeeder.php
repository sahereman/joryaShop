<?php

use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        User::all()->each(function (User $user) {

            factory(UserAddress::class, random_int(2, 4))->create([
                'user_id' => $user->id,
            ]);

            $address = UserAddress::where('user_id', $user->id)->first();
            $address->is_default = true;
            $address->save();
        });
    }
}
