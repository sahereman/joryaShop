<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuctionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auction_products', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('product_id')->nullable(false)->comment('product-id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->unsignedDecimal('trigger_price', 8, 2)->nullable()->comment('起拍价'); // 即: 期望价
            $table->unsignedDecimal('current_price', 8, 2)->nullable()->comment('当前价');
            $table->unsignedDecimal('final_price', 8, 2)->nullable()->comment('成交价');

            $table->unsignedDecimal('step', 8, 2)->nullable()->comment('加价幅度');

            $table->unsignedInteger('max_participant_number')->nullable(false)->default(0)->comment('最大竞拍人数');
            $table->unsignedInteger('max_deal_number')->nullable(false)->default(0)->comment('最大成交人数');

            $table->string('status')->nullable()->default('preparing')->comment('Status'); // Status: preparing(尚未开始) | bidding(拍卖进行中) | signed(成交) | abortive(流拍)

            $table->timestamp('started_at')->nullable()->comment('Period Started at');
            $table->timestamp('stopped_at')->nullable()->comment('Period Stopped at');

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
        Schema::dropIfExists('auction_products');
    }
}
