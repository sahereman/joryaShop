<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{


    /*
     * 订单表字段设计问题
     *
     * 1.  支付成功 字段冗余
     * 2.  关闭订单 字段冗余
     * 3.  收货时间 字段不需要
     * 4.  订单完成 字段不需要
     * 5.  上传证件图片集 字段???
     * 6.  评论时间 字段不合理
     * 7.  用户收货地址问题(重要) , 问题思考: 假设用户下订单后,更改了用户收货地址 , 订单数据将出现 原始性问题
     */


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_sn')->nullable(false)->comment('order-sn');

            $table->unsignedInteger('user_id')->nullable(false)->comment('user-id');
            $table->foreign('user_id')->references('id')->on('users');


            $table->unsignedInteger('user_address_id')->nullable(false)->comment('user-address-id');
            $table->foreign('user_address_id')->references('id')->on('user_addresses');


            $table->string('status')->nullable(false)->default('paying')->comment('order-status:paying[待支付];closed[交易关闭:主动|自动取消订单];shipping[待发货];receiving[待收货];refunding[售后状态];completed[交易结束:已确认]')->index();
            $table->string('currency')->nullable(false)->default('CNY')->comment('payment-currency[支付币种]:CNY|USD|etc');
//            $table->boolean('is_paid')->nullable(false)->default(false)->comment('is-paid');
            $table->timestamp('paid_at')->nullable()->comment('paid-at');
            $table->string('payment_method')->nullable()->comment('payment-method:alipay|wechat|papal');
            $table->string('payment_sn')->nullable()->comment('payment-sn');
            $table->boolean('is_closed')->nullable(false)->default(false)->comment('is-closed');
//            $table->timestamp('closed_at')->nullable()->comment('closed-at');
            $table->boolean('is_shipped')->nullable(false)->default(false)->comment('is-shipped');
            $table->timestamp('shipped_at')->nullable()->comment('shipped-at');
            $table->string('shipment_company')->nullable()->comment('shipment-company');
            $table->string('shipment_sn')->nullable()->comment('shipment-sn');
            $table->boolean('is_received')->nullable(false)->default(false)->comment('is-received');
            $table->timestamp('received_at')->nullable()->comment('received-at');
            $table->boolean('is_refunded')->nullable(false)->default(false)->comment('is-refunded');
            $table->timestamp('refunded_at')->nullable()->comment('refunded-at');
            $table->boolean('is_completed')->nullable(false)->default(false)->comment('is-completed');
            $table->timestamp('completed_at')->nullable()->comment('completed-at');
            $table->boolean('is_commented')->nullable(false)->default(false)->comment('is-commented');
            $table->timestamp('commented_at')->nullable()->comment('commented-at');
            $table->json('snapshot')->nullable(false)->comment('snapshot-of-order-item-data-in-json-format[data:product_sku_id&price&number]');
            $table->json('photos')->nullable()->comment('上传证件图片集');
            $table->unsignedDecimal('total_shipping_fee', 8, 2)->nullable(false)->default(0)->comment('total-shipping-fee:运费[采用当前币种换算表示]');
            $table->unsignedDecimal('total_amount', 8, 2)->nullable(false)->comment('total-amount:商品合计金额[采用当前币种换算表示]');
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
        Schema::dropIfExists('orders');
    }
}
