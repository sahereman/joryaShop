<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('user-id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('order_id')->nullable(false)->comment('order-id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->unsignedInteger('product_sku_id')->nullable(false)->comment('product-sku-id');
            $table->foreign('product_sku_id')->references('id')->on('product_skus');
            $table->unsignedDecimal('price', 8, 2)->nullable(false)->comment('商品价格[沿用父订单表中的币种换算表示]');
            $table->unsignedInteger('number')->nullable(false)->comment('购买数量');
            $table->softDeletes(); // timestamp deleted_at used for soft deletes.
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
        Schema::dropIfExists('order_items');
    }
}
