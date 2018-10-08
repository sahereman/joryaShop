<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('用户名');
            $table->string('email')->unique()->comment('邮箱');
            $table->string('avatar')->comment('头像');
            $table->string('password')->comment('密码');
            $table->string('real_name')->nullable()->comment('real-name');
            $table->string('gender')->nullable()->comment('gender');
            $table->string('qq')->nullable()->comment('QQ');
            $table->string('wechat')->nullable()->comment('WeChat');
            $table->string('phone')->nullable()->comment('phone');
            $table->string('facebook')->nullable()->comment('Facebook');
            $table->string('language')->nullable()->comment('language'); // 用户常用语言
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
