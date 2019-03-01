<?php

/*商品属性 2019-03-01*/
//use Illuminate\Database\Migrations\Migration;
//use Illuminate\Database\Schema\Blueprint;
//use Illuminate\Support\Facades\Schema;
//
//class CreateAttrProductsTable extends Migration
//{
//    /**
//     * Run the migrations.
//     * @return void
//     */
//    public function up()
//    {
//        Schema::create('attr_products', function (Blueprint $table) {
//            $table->increments('id');
//
//            $table->unsignedInteger('attr_id')->nullable(false)->comment('attr-id');
//            $table->foreign('attr_id')->references('id')->on('attrs')->onDelete('cascade');
//
//            $table->unsignedInteger('product_id')->nullable(false)->comment('product-id');
//            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
//
//            $table->timestamps();
//        });
//    }
//
//    /**
//     * Reverse the migrations.
//     * @return void
//     */
//    public function down()
//    {
//        Schema::dropIfExists('attr_products');
//    }
//}
/*商品属性 2019-03-01*/
