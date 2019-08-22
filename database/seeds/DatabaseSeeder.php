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

        //国家地区
        $this->call(CountryProvincesSeeder::class);

        // 产品
        $this->call(ProductCategoriesSeeder::class);
        $this->call(ProductLocationsSeeder::class);
        $this->call(ProductServicesSeeder::class);
        $this->call(ProductsSeeder::class);
        $this->call(ProductSkusSeeder::class);

        // 用户
        $this->call(UsersSeeder::class);
        $this->call(UserAddressesSeeder::class);
        $this->call(UserFavouritesSeeder::class);
        $this->call(UserHistoriesSeeder::class);

        // 购物车
        $this->call(CartsSeeder::class);

        // 文章
        $this->call(ArticleCategoriesSeeder::class);
        $this->call(ArticlesSeeder::class);

        // 其他
        $this->call(BannersSeeder::class);
        $this->call(CountryCodesSeeder::class);
        $this->call(ExchangeRatesSeeder::class);
        $this->call(PostersSeeder::class);
        $this->call(ShipmentCompaniesSeeder::class);

        // 订单
        $this->call(OrdersSeeder::class);
        $this->call(OrderItemsSeeder::class);
        $this->call(OrderRefundsSeeder::class);
        $this->call(RefundReasonsSeeder::class);
        // $this->call(ProductCommentsSeeder::class);

        // 菜单
        $this->call(MenusSeeder::class);

        // 留言板
        $this->call(FeedbacksSeeder::class);

        //邮件模板
        $this->call(EmailTemplatesSeeder::class);

        // 根据商品名称生成 Slug
        // $this->call(ProductSlugsSeeder::class);

        // SKU 属性
        $this->call(AttrsSeeder::class);
        $this->call(AttrValuesSeeder::class);
        $this->call(ProductAttrsSeeder::class);
        $this->call(ProductSkuAttrValuesSeeder::class);

        // 商品参数
        $this->call(ParamsSeeder::class);
        $this->call(ParamValuesSeeder::class);
        $this->call(ProductParamsSeeder::class);

        // 产品 Q&A
        $this->call(ProductQuestionAnswersSeeder::class);

        // 优惠券
        $this->call(CouponsSeeder::class);
        $this->call(UserCouponsSeeder::class);

        // 定制商品
        $this->call(CustomAttrsSeeder::class);
        $this->call(CustomAttrValuesSeeder::class);

        //分销级别
        $this->call(DistributionLevelsSeeder::class);

        //运费模板
        $this->call(ShipmentTemplatesSeeder::class);

    }
}
