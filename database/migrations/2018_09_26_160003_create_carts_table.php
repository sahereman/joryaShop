<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        // note: unique-key: user_id-product_sku_id
        Schema::create('carts', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable(false)->comment('user-id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('product_sku_id')->nullable(false)->comment('product-sku-id');
            $table->foreign('product_sku_id')->references('id')->on('product_skus')->onDelete('cascade');

            $table->unsignedInteger('number')->nullable(false)->comment('购买数量');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
