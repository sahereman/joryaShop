<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserAddress;

class UserAddressesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // UserAddress::truncate();
        User::all()->each(function (User $user) {
            factory(UserAddress::class)->create(['user_id' => $user->id, 'is_default' => true]);
        });
    }
}
