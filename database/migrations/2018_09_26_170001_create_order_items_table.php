<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('order_id')->nullable(false)->comment('order-id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->unsignedInteger('product_sku_id')->nullable(false)->comment('product-sku-id');
            $table->foreign('product_sku_id')->references('id')->on('product_skus')->onDelete('cascade');

            $table->unsignedDecimal('price', 8, 2)->nullable(false)->comment('商品价格/USD');
            $table->unsignedInteger('number')->nullable(false)->comment('购买数量');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
