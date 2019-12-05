<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->post('wang_editor/images', 'WangEditorController@images')->name('admin.wang_editor.images');/*WangEditor上传图片*/
    $router->get('dashboard', 'PagesController@dashboard')->name('admin.dashboard');

    $router->get('/', 'PagesController@index')->name('admin.root');

    /*系统设置*/
    $router->get('configs', 'ConfigsController@index')->name('admin.configs.index');/*详情*/
    $router->post('configs/submit', 'ConfigsController@submit')->name('admin.configs.submit');/*提交*/

    /*用户*/
    $router->get('users/send_message/{id?}', 'UsersController@sendMessageShow')->name('admin.users.send_message.show'); /*群发站内信 页面*/
    $router->post('users/send_message', 'UsersController@sendMessageStore')->name('admin.users.send_message.store'); /*群发站内信 请求处理*/
    $router->get('users/send_email/{id?}', 'UsersController@sendEmailShow')->name('admin.users.send_email.show'); /*群发邮件 页面*/
    $router->post('users/send_email', 'UsersController@sendEmailStore')->name('admin.users.send_email.store'); /*群发邮件 请求处理*/
    $router->get('users/send_coupon/{user?}', 'UsersController@sendCouponShow')->name('admin.users.send_coupon.show'); /*群发优惠券 页面*/
    $router->post('users/send_coupon', 'UsersController@sendCouponStore')->name('admin.users.send_coupon.store'); /*群发优惠券 请求处理*/
    /* --- */
    $router->get('users', 'UsersController@index')->name('admin.users.index');
    $router->get('users/create', 'UsersController@create')->name('admin.users.create');
    $router->get('users/{id}', 'UsersController@show')->name('admin.users.show');
    $router->get('users/{id}/edit', 'UsersController@edit')->name('admin.users.edit');
    $router->put('users/{id}', 'UsersController@update')->name('admin.users.update');
    $router->delete('users/{id}', 'UsersController@destroy')->name('admin.users.destroy');

    /*商品订单*/
    $router->get('orders', 'OrdersController@index')->name('admin.orders.index');/*列表*/
    $router->get('orders/{order}', 'OrdersController@show')->name('admin.orders.show');/*详情*/
    $router->delete('orders/{order}/delete', 'OrdersController@delete')->name('admin.orders.delete');/*删除*/
    $router->post('orders/{order}/modify', 'OrdersController@modify')->name('admin.orders.modify');/*修改订单运费*/
    $router->post('orders/{order}/ship', 'OrdersController@ship')->name('admin.orders.ship');/*发货*/

    /*售后订单*/
    $router->get('order_refunds', 'OrderRefundsController@index')->name('admin.order_refunds.index');/*列表*/
    $router->get('order_refunds/{refund}', 'OrderRefundsController@show')->name('admin.order_refunds.show');/*详情*/
    $router->post('order_refunds/{refund}/check', 'OrderRefundsController@check')->name('admin.order_refunds.check');/*审核通过*/
    $router->post('order_refunds/{refund}/receive', 'OrderRefundsController@receive')->name('admin.order_refunds.receive');/*收货并退款*/

    /*订单回收站*/
    $router->get('order_recycles', 'OrderRecyclesController@index')->name('admin.order_recycles.index');/*列表*/
    $router->delete('order_recycles/{order}/delete', 'OrderRecyclesController@delete')->name('admin.order_recycles.delete');/*永久删除*/

    /*SKU 属性*/
    $router->resource('attrs', AttrsController::class)->names('admin.attrs');
    $router->put('attrs/{value}/delete_value_photo', 'AttrsController@deleteValuePhoto')->name('admin.attrs.delete_value_photo');

    /*商品参数*/
    $router->resource('params', ParamsController::class)->names('admin.params');

    /*优惠券*/
    $router->resource('coupons', CouponsController::class)->names('admin.coupons');

    /*产品分类*/
    $router->resource('product_categories', ProductCategoriesController::class)->names('admin.product_categories');

    /*产品*/
    $router->resource('products', ProductsController::class)->names('admin.products');
    $router->post('products/{product}/duplicate', 'ProductsController@duplicate')->name('admin.products.duplicate');
    $router->post('products/{product}/sort_photos', 'ProductsController@sortPhotos')->name('admin.products.sort_photos');
    $router->get('products/{product}/sku_generator', 'ProductsController@skuGeneratorShow')->name('admin.products.sku_generator_show');
    $router->post('products/{product}/sku_generator', 'ProductsController@skuGeneratorStore')->name('admin.products.sku_generator_store');
    $router->get('products/{product}/sku_editor', 'ProductsController@skuEditorShow')->name('admin.products.sku_editor_show');
    $router->post('products/{product}/sku_editor', 'ProductsController@skuEditorStore')->name('admin.products.sku_editor_store');
    $router->put('product_skus/{sku}/del_img', 'ProductSkusController@delImg')->name('admin.product_skus.del_img');

    /*产品SKU*/
    $router->resource('product_skus', ProductSkusController::class)->names('admin.product_skus');

    /*限时商品*/
    $router->resource('period_products', PeriodProductsController::class)->names('admin.period_products');

    /*拍卖商品*/
    $router->resource('auction_products', AuctionProductsController::class)->names('admin.auction_products');

    /*优惠商品*/
    $router->resource('discount_products', DiscountProductsController::class)->names('admin.discount_products');

    /*定制商品 SKU 属性*/
    $router->resource('custom_attrs', CustomAttrsController::class)->names('admin.custom_attrs');

    /*产品仓库*/
    $router->resource('product_locations', ProductLocationsController::class)->names('admin.product_locations');

    /*产品服务*/
    $router->resource('product_services', ProductServicesController::class)->names('admin.product_services');

    /*评价*/
    $router->delete('product_comments/{comment}/delete', 'ProductCommentsController@delete')->name('admin.product_comments.delete');

    /*FAQs*/
    $router->resource('product_faqs', ProductFaqsController::class)->names('admin.product_faqs');

    /*广告位*/
    $router->resource('posters', PostersController::class)->names('admin.posters');

    /*文章*/
    $router->resource('article_categories', ArticleCategoriesController::class)->names('admin.article_categories');
    $router->resource('articles', ArticlesController::class)->names('admin.articles');

    /*Banner图*/
    $router->resource('banners', BannersController::class)->names('admin.banners');

    /*汇率管理*/
    $router->resource('exchange_rates', ExchangeRatesController::class)->names('admin.exchange_rates');

    /*手机号国家管理*/
    $router->resource('country_codes', CountryCodesController::class)->names('admin.country_codes');

    /*快递公司管理*/
    $router->resource('shipment_companies', ShipmentCompaniesController::class)->names('admin.shipment_companies');

    /*导航菜单管理*/
    $router->resource('menus', MenusController::class)->names('admin.menus');

    /*退款原因管理*/
    $router->resource('refund_reasons', RefundReasonsController::class)->names('admin.refund_reasons');

    /*留言板*/
    $router->resource('feedbacks', FeedbacksController::class)->names('admin.feedbacks');

    /*邮件模板*/
    $router->get('email_templates/preview/{email_template}', 'EmailTemplatesController@preview')->name('admin.email_templates.preview');
    $router->resource('email_templates', EmailTemplatesController::class)->names('admin.email_templates');

    /*运费模板*/
    $router->resource('shipment_templates', ShipmentTemplatesController::class)->names('admin.shipment_templates');
    $router->resource('shipment_template_plans', ShipmentTemplatePlansController::class)->names('admin.shipment_template_plans');

    /*分销等级*/
    $router->resource('distribution_levels', DistributionLevelsController::class)->names('admin.distribution_levels');

    /*Email Log*/
    $router->resource('email_logs', EmailLogsController::class)->names('admin.email_logs');

    /*Fake Review*/
    $router->resource('fake_reviews', FakeReviewsController::class)->names('admin.fake_reviews');

    // $router->resource('example', ExampleController::class)->names('admin.example');
    // $router->get('example', 'ExampleController@index')->name('admin.example.index');
    // $router->get('example/create', 'ExampleController@create')->name('admin.example.create');
    // $router->get('example/{id}', 'ExampleController@show')->name('admin.example.show');
    // $router->get('example/{id}/edit', 'ExampleController@edit')->name('admin.example.edit');
    // $router->post('example', 'ExampleController@store')->name('admin.example.store');
    // $router->put('example/{id}', 'ExampleController@update')->name('admin.example.update');
    // $router->delete('example/{id}', 'ExampleController@destroy')->name('admin.example.destroy');
});
