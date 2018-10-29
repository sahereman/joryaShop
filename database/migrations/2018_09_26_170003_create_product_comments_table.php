<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCommentsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('product_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable(false)->default(0)->comment('parent-comment-id:用于追加评论');

            $table->unsignedInteger('user_id')->nullable(false)->default(0)->comment('user-id[user_id=0:来自卖家的评论]');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('order_id')->nullable(false)->comment('order-id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->unsignedInteger('order_item_id')->nullable(false)->comment('order-item-id');
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');

            $table->unsignedInteger('product_id')->nullable(false)->comment('product-id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->unsignedInteger('composite_index')->nullable(false)->default(5)->comment('综合评分');
            $table->unsignedInteger('description_index')->nullable(false)->default(5)->comment('描述相符');
            $table->unsignedInteger('shipment_index')->nullable(false)->default(5)->comment('物流服务');
            $table->string('content')->nullable(false)->comment('comment-content');
            $table->text('photos')->nullable()->comment('图片集');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_comments');
    }
}
