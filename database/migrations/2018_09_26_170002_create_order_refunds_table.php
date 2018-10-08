<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('order_id')->nullable(false)->comment('order-id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->json('seller_info')->nullable()->comment('seller-info-data-for-reshipping-in-json-format[data:name&phone&address]'); // 卖家信息

            $table->string('type')->nullable(false)->comment('order-refund-type:refund_money|refund_product')->index();

            $table->string('remark_by_user')->nullable()->comment('remark-by-user');
            $table->string('remark_by_seller')->nullable()->comment('remark-by-seller');
            $table->string('remark_by_shipment')->nullable()->comment('remark-by-shipment');
            $table->string('shipment_sn')->nullable()->comment('refund-shipment-sn');
            $table->string('shipment_company')->nullable()->comment('refund-shipment-company');
            $table->json('photos_for_refund')->nullable()->comment('用户上传商品及证件图片集');
            $table->json('photos_for_shipment')->nullable()->comment('退货物流证件图片集');

            $table->timestamp('refunded_at')->nullable()->comment('卖家退款时间');

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
        Schema::dropIfExists('order_refunds');
    }
}
