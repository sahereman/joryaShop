<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFakeReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fake_reviews', function (Blueprint $table) {
            $table->increments('id');

            $table->string('photo')->nullable()->comment('商品图片');
            $table->string('review')->nullable()->comment('用户评论内容');
            $table->string('name')->nullable()->comment('用户名');
            $table->timestamp('reviewed_at')->nullable()->comment('用户评论时间');
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
        Schema::dropIfExists('fake_reviews');
    }
}
