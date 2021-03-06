<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSkuCustomAttrValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sku_custom_attr_values', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('product_sku_id')->nullable(false)->comment('product_sku-id');
            $table->foreign('product_sku_id')->references('id')->on('product_skus')->onDelete('cascade');

            $table->string('type')->nullable(false)->default('BASE')->comment('属性类型'); // Types: BASE | HAIR | COLOR | SERVICE

            $table->string('name')->nullable(false)->comment('定制商品 SKU 属性名称');

            $table->string('value')->nullable(false)->comment('定制商品 SKU 属性值');

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
        Schema::dropIfExists('product_sku_custom_attr_values');
    }
}
