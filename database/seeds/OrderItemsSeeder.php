<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\OrderItem;

class OrderItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderItem::truncate();
        $users = User::all();
    }
}
