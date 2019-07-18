<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');

            $table->string('sn')->nullable(false)->comment('sn');
            $table->unique('sn');

            $table->unsignedInteger('user_id')->nullable(false)->comment('user-id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('currency')->nullable(false)->default('USD')->comment('payment-currency[支付币种]:USD|CNY|etc');
            $table->unsignedDecimal('payment_amount', 8, 2)->nullable(false)->comment('payment-amount:支付金额[采用当前币种换算表示]');
            $table->string('payment_method')->nullable()->comment('payment-method:alipay|wechat|paypal');
            $table->string('payment_sn')->nullable()->comment('payment-sn');
            $table->timestamp('paid_at')->nullable()->comment('支付订单时间');

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
        Schema::dropIfExists('payments');
    }
}
