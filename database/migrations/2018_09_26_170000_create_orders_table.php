<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
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
            $table->unique('order_sn');

            $table->unsignedInteger('user_id')->nullable(false)->comment('user-id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->json('user_info')->nullable(false)->comment('user-info-data-for-shipping-in-json-format[data:name&phone&address]'); // 买家信息

            /**
             * Order Status Workflow:
             *
             * paying --> | --> closed
             *            |
             *            | --> shipping  | -->receiving --> | --> completed
             *                            |                  |
             *                            | --> refunding    | --> refunding
             *                            (refund[仅退款])    (refund_with_shipment[退货并退款])
             *
             */
            $table->string('status')->nullable(false)->default('paying')->comment('order-status:paying[待支付];closed[交易关闭:主动|自动取消订单];shipping[待发货];receiving[待收货];refunding[售后状态:售后中];completed[交易结束:已确认]')->index(); // 最终状态只有三种：closed, refunding, completed

            $table->string('currency')->nullable(false)->default('CNY')->comment('payment-currency[支付币种]:CNY|USD|etc');
            $table->string('payment_method')->nullable()->comment('payment-method:alipay|wechat|paypal');
            $table->string('payment_sn')->nullable()->comment('payment-sn');
            $table->timestamp('paid_at')->nullable()->comment('支付订单时间');

            $table->timestamp('closed_at')->nullable()->comment('取消订单，交易关闭时间');

            $table->string('shipment_sn')->nullable()->comment('shipment-sn');
            $table->string('shipment_company')->nullable()->comment('shipment-company');
            $table->timestamp('shipped_at')->nullable()->comment('卖家配送发货时间');

            $table->timestamp('completed_at')->nullable()->comment('确认收货，交易结束时间');
            $table->timestamp('commented_at')->nullable()->comment('订单商品评价时间');

            $table->json('snapshot')->nullable(false)->comment('snapshot-of-order-item-data-in-json-format[data:sku_id&price&number]');

            $table->unsignedDecimal('total_shipping_fee', 8, 2)->nullable(false)->default(0)->comment('total-shipping-fee:运费[采用当前币种换算表示]');
            $table->unsignedDecimal('total_amount', 8, 2)->nullable(false)->comment('total-amount:商品合计金额[采用当前币种换算表示]');
            $table->string('remark')->nullable()->comment('订单备注');

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
