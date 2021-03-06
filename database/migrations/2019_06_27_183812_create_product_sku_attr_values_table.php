<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSkuAttrValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sku_attr_values', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('product_sku_id')->nullable(false)->comment('product-sku-id');
            $table->foreign('product_sku_id')->references('id')->on('product_skus')->onDelete('cascade');

            $table->unsignedInteger('product_attr_id')->nullable(false)->comment('product-attr-id');
            $table->foreign('product_attr_id')->references('id')->on('product_attrs')->onDelete('cascade');

            $table->string('value')->nullable(false)->comment('SKU 属性值');

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
        Schema::dropIfExists('product_sku_attr_values');
    }
}
