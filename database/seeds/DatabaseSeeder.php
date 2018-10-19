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

        //产品
        $this->call(ProductCategoriesSeeder::class);
        $this->call(ProductsSeeder::class);
        $this->call(ProductSkusSeeder::class);

        //用户
        $this->call(UsersSeeder::class);
        $this->call(UserAddressesSeeder::class);
        $this->call(UserFavouritesSeeder::class);
        $this->call(UserHistoriesSeeder::class);

        //购物车
        $this->call(CartsSeeder::class);

        //订单
        $this->call(OrdersSeeder::class);
        $this->call(OrderItemsSeeder::class);

        //评论
//        $this->call(ProductCommentsSeeder::class);

        //其他
        $this->call(BannersSeeder::class);
        $this->call(CountryCodesSeeder::class);
        $this->call(ExchangeRatesSeeder::class);
        $this->call(ArticlesSeeder::class);
        $this->call(PostersSeeder::class);

        // Pertaining Order
        // $this->call(OrderRefundsSeeder::class);
        // $this->call(ProductCommentsSeeder::class);
    }
}
