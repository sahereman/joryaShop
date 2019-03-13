<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable()->comment('user-id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('name')->nullable()->comment('用户名');
            $table->string('gender')->nullable()->comment('性别:male|female');

            $table->string('email')->nullable()->comment('邮箱');

            $table->string('phone')->nullable()->comment('手机');
            $table->text('content')->nullable()->comment('留言内容');
            $table->string('type')->nullable()->comment('留言类型:subscription|consultancy');
            $table->boolean('is_check')->nullable(false)->default(false)->comment('是否为已处理');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feedbacks');
    }
}
