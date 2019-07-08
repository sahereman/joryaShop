<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAuctionLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_auction_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('auction_product_id')->nullable(false)->comment('auction-product-id');
            $table->foreign('auction_product_id')->references('id')->on('auction_products')->onDelete('cascade');

            $table->unsignedInteger('user_id')->nullable(false)->comment('user-id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedDecimal('bid_price', 8, 2)->nullable(false)->default(0.00)->comment('出价');

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
        Schema::dropIfExists('product_sku_auction_logs');
    }
}
