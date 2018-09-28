<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Order::truncate();
        $users = User::all();
    }
}
