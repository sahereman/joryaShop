<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable()->comment('name');
            $table->string('type')->nullable(false)->default('discount')->comment('优惠券类型'); // Types: discount(折扣) | reduction(满减)
            $table->unsignedDecimal('discount', 3, 2)->nullable(false)->default(0.00)->comment('折扣');
            $table->unsignedDecimal('reduction', 8, 2)->nullable(false)->default(0.00)->comment('满减');
            $table->unsignedDecimal('threshold', 8, 2)->nullable(false)->default(0.00)->comment('优惠策略之消费金额触发阈值');
            $table->unsignedInteger('number')->nullable()->default(null)->comment('数量'); // null: 不限量
            $table->unsignedInteger('allowance')->nullable(false)->default(1)->comment('单人领取限额');
            $table->json('designated_product_types')->nullable(false)->comment('指定商品类型'); // product_types: common | period | auction
            $table->string('scenario')->nullable(false)->comment('用户领取场景');
            $table->unsignedSmallInteger('sort')->nullable(false)->default(0)->comment('排序值');
            $table->timestamp('started_at')->nullable()->comment('Started at');
            $table->timestamp('stopped_at')->nullable()->comment('Stopped at');

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
        Schema::dropIfExists('coupons');
    }
}
