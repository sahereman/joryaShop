<?php

use App\Models\Coupon;
use App\Models\User;
use App\Models\UserCoupon;
use Illuminate\Database\Seeder;

class UserCouponsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(function (User $user) {
            Coupon::all()->each(function (Coupon $coupon) use ($user) {
                factory(UserCoupon::class)->create([
                    'user_id' => $user->id,
                    'coupon_id' => $coupon->id
                ]);
            });
        });
    }
}
