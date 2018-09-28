<?php

use Illuminate\Database\Seeder;
use App\Models\UserFavourite;

class UserFavouritesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserFavourite::truncate();
        factory(UserFavourite::class, 10)->create();
        $userFavourite = UserFavourite::find(1);
        $userFavourite->product_id = 1;
        $userFavourite->save();
    }
}
