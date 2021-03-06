<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('product_category_id')->nullable(false)->comment('product-category-id');
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('cascade');

            $table->string('name_en')->nullable(false)->comment('英文名称');
            $table->string('name_zh')->nullable(false)->comment('中文名称'); // 备用字段
            $table->text('description_en')->nullable()->comment('英文描述');
            $table->text('description_zh')->nullable()->comment('中文描述'); // 备用字段
            $table->longText('content_en')->nullable()->comment('英文内容');
            $table->text('content_zh')->nullable()->comment('中文内容'); // 备用字段
            $table->string('thumb')->nullable()->comment('缩略图');
            $table->json('photos')->nullable()->comment('图片集');

            // 2019-01-22
            $table->boolean('is_base_size_optional')->nullable(false)->default(true)->comment('SKU 参数 base_size 是否可选');
            $table->boolean('is_hair_colour_optional')->nullable(false)->default(true)->comment('SKU 参数 hair_colour 是否可选');
            $table->boolean('is_hair_density_optional')->nullable(false)->default(true)->comment('SKU 参数 hair_density 是否可选');
            // 2019-01-22

            $table->unsignedDecimal('shipping_fee', 8, 2)->nullable()->comment('运费');
            $table->unsignedInteger('stock')->nullable(false)->default(0)->comment('库存');
            $table->unsignedInteger('sales')->nullable(false)->default(0)->comment('销量');
            $table->unsignedInteger('index')->nullable(false)->default(0)->comment('综合指数'); // according to comments & sum(order_item.number)
            $table->unsignedInteger('heat')->nullable(false)->default(0)->comment('人气|热度'); // according to favourites & count(order_items)
            $table->unsignedDecimal('price', 8, 2)->nullable()->comment('价格:商品基准价格'); // managed by product price observer

            $table->boolean('is_index')->nullable(false)->default(false)->comment('是否为推荐商品[在首页展示]');
            $table->boolean('on_sale')->nullable(false)->default(true)->comment('是否在售');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

/*
 * stock: according to order[store(-)|close(+)|refund(+)]
 *          OrderItem[Observer] + OrderClosed[Event|EventListener] + OrderRefundedWithShipment[Event|EventListener]
 *          Status: TODO with OrderRefundedWithShipment[Event|EventListener] ... @ admin
 *
 * sales: according to order[complete(+)] (Never Decrement for now ...)
 *          OrderCompleted[Event|EventListener] + AutoCompleteOrder[Job]
 *          Status: Done.
 *
 * index: according to order[store(+)|close(-)|refund(-)] & product_comment[store(+)]
 *          OrderItem[Observer] + OrderClosed[Event|EventListener] + OrderRefunding[Event|EventListener] + ProductComment[Observer]
 *          Status: Done.
 *
 * heat: according to order[store(+)|close(-)|refund(-)] & favourite[store(+)|delete(-)]
 *          OrderItem[Observer] + OrderClosed[Event|EventListener] + OrderRefunding[Event|EventListener] + UserFavourite[Observer]
 *          Status: Done.
 */
