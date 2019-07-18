<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentRelatedColumnsOfOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('payment_id')->nullable()->comment('payment-id')->after('user_id');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');

            $table->dropColumn('payment_method');
            $table->dropColumn('payment_sn');
            $table->dropColumn('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->comment('payment-method:alipay|wechat|paypal')->after('currency');
            $table->string('payment_sn')->nullable()->comment('payment-sn')->after('payment_method');
            $table->timestamp('paid_at')->nullable()->comment('支付订单时间')->after('payment_sn');

            $table->dropForeign(['payment_id']);
            $table->dropColumn('payment_id');
        });
    }
}
