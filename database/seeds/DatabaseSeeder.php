<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //后台
        $this->call(AdminTablesSeeder::class);

        //用户
        $this->call(UsersSeeder::class);
    }
}
