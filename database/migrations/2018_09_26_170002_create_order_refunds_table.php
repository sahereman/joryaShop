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
            $table->unsignedInteger('user_id')->nullable(false)->comment('user-id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedInteger('order_id')->nullable(false)->comment('order-id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->unsignedInteger('address_id')->nullable(false)->comment('address-id'); // seller's address
            $table->string('order_refund_sn')->nullable(false)->comment('order-refund-sn');
            $table->string('type')->nullable(false)->comment('order-refund-type:refund_money|refund_product')->index();
            $table->string('ps_user')->nullable()->comment('remark from user');
            $table->string('ps_seller')->nullable()->comment('remark from seller');
            $table->json('re_photos')->nullable()->comment('上传商品及证件图片集');
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
        Schema::dropIfExists('order_refunds');
    }
}
