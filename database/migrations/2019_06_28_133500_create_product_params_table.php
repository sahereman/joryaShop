<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_params', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('product_id')->nullable(false)->comment('product-id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->string('name')->nullable(false)->comment('商品参数名称');
            $table->string('value')->nullable(false)->comment('商品参数值');

            $table->unsignedSmallInteger('sort')->nullable(false)->default(0)->comment('排序值');

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
        Schema::dropIfExists('product_params');
    }
}
