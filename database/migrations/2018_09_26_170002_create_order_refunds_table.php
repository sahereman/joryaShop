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
            $table->string('refund_sn')->nullable(false)->comment('refund-sn');
            $table->unique('refund_sn');

            $table->unsignedInteger('order_id')->nullable(false)->comment('order-id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->json('seller_info')->nullable()->comment('seller-info-data-for-reshipping-in-json-format[data:name&phone&address]'); // 卖家信息

            /**
             * Order Refund Status Workflow:
             *
             * refund[仅退款]
             * checking --> | --> refunded
             *              |
             *              | --> declined
             *
             * refund_with_shipment[退货并退款]
             * checking --> | --> shipping -> receiving -> refunded
             *              |
             *              | --> declined
             *
             */
            $table->string('type')->nullable(false)->comment('order-refund-type:refund[仅退款]|refund_with_shipment[退货并退款]')->index();
            $table->string('status')->nullable(false)->default('checking')->comment('order-status:checking[待审核];shipping[待发货];receiving[待收货];completed[已退款];declined[已拒绝]')->index(); // 最终状态只有两种：refunded, declined

            /*当前默认退款模式：全额退款，所有退款按原有币种原路返回*/
            $table->unsignedDecimal('amount', 8, 2)->nullable()->comment('amount:退款金额[采用当前币种换算表示]'); // 备用字段

            $table->string('remark_from_user')->nullable()->comment('remark from user');
            $table->string('remark_from_seller')->nullable()->comment('remark from seller');
            $table->string('remark_for_shipment_from_user')->nullable()->comment('remark for shipment from user');
            $table->string('remark_for_shipment_from_seller')->nullable()->comment('remark for shipment from seller');
            $table->string('shipment_company')->nullable()->comment('refund-shipment-company');
            $table->string('shipment_sn')->nullable()->comment('refund-shipment-sn');
            $table->json('photos_for_refund')->nullable()->comment('用户上传商品及证件图片集');
            $table->json('photos_for_shipment')->nullable()->comment('退货物流证件图片集');

            $table->timestamp('checked_at')->nullable()->comment('卖家通过审核时间');
            $table->timestamp('shipped_at')->nullable()->comment('买家配送发货时间');
            $table->timestamp('refunded_at')->nullable()->comment('卖家同意退款时间');
            $table->timestamp('to_be_declined_at')->nullable()->comment('系统自动拒绝售后申请时间');
            $table->timestamp('declined_at')->nullable()->comment('卖家拒绝退款时间');

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
