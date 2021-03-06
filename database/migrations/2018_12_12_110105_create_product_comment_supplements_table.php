<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCommentSupplementsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('product_comment_supplements', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('product_comment_id')->nullable(false)->comment('product_comment-id[父级评论id]');
            $table->foreign('product_comment_id')->references('id')->on('product_comments')->onDelete('cascade');

            $table->unsignedInteger('user_id')->nullable()->comment('user-id[user_id=null:来自卖家的评论]');

            $table->string('content')->nullable(false)->comment('comment-content');
            $table->json('photos')->nullable()->comment('图片集');
            $table->string('from')->nullable(false)->comment('评论来自:user用户|seller卖家');

            $table->softDeletes(); // timestamp deleted_at used for soft deletes.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_comment_supplements');
    }
}
