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
        $this->call(AdminTablesSeeder::class);
        $this->call(ConfigsSeeder::class);

        //用户
        $this->call(UsersSeeder::class);

        // Pertaining Product
        // $this->call(ProductCategoriesSeeder::class);
        // $this->call(ProductsSeeder::class);
        // $this->call(ProductSkusSeeder::class);

        // Pertaining User
        $this->call(UserFavouritesSeeder::class);
        $this->call(UserHistoriesSeeder::class);
        $this->call(UserAddressesSeeder::class);
        $this->call(CartsSeeder::class);

        // Pertaining Order
        // $this->call(OrdersSeeder::class);
        // $this->call(OrderItemsSeeder::class);
        // $this->call(OrderRefundsSeeder::class);
        // $this->call(ProductCommentsSeeder::class);

        // Common
        $this->call(BannersSeeder::class);
        $this->call(PagesSeeder::class);
        $this->call(PostersSeeder::class);
        $this->call(ExchangeRatesSeeder::class);
    }
}
