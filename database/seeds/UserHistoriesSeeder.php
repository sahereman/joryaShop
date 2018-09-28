<?php

use Illuminate\Database\Seeder;
use App\Models\UserHistory;

class UserHistoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserHistory::truncate();
        factory(UserHistory::class, 10)->create();
        $userHistory = UserHistory::find(1);
        $userHistory->product_id = 1;
        $userHistory->save();
    }
}
