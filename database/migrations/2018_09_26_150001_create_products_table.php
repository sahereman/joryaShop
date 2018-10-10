<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('product_category_id')->nullable(false)->comment('product-category-id');
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('cascade');

            $table->string('name_en')->nullable(false)->comment('英文名称');
            $table->string('name_zh')->nullable(false)->comment('中文名称');
            $table->string('description_en')->nullable()->comment('英文描述');
            $table->string('description_zh')->nullable()->comment('中文描述');
            $table->string('content_en')->nullable()->comment('英文内容');
            $table->string('content_zh')->nullable()->comment('中文内容');
            $table->string('thumb')->nullable()->comment('缩略图');
            $table->json('photos')->nullable()->comment('图片集');

            $table->unsignedDecimal('shipping_fee', 8, 2)->nullable()->comment('运费');
            $table->unsignedInteger('stock')->nullable(false)->default(0)->comment('库存');
            $table->unsignedInteger('sales')->nullable(false)->default(0)->comment('销量');
            $table->unsignedInteger('index')->nullable(false)->default(0)->comment('综合指数'); // according to comments
            $table->unsignedInteger('heat')->nullable(false)->default(0)->comment('人气|热度'); // according to favourites
            $table->unsignedDecimal('price', 8, 2)->nullable(false)->comment('价格:呈现为SKU最低价'); // managed by product price observer

            $table->boolean('is_index')->nullable(false)->default(false)->comment('是否为推荐商品[在首页展示]');
            $table->boolean('on_sale')->nullable(false)->default(true)->comment('是否在售');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
