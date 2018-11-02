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
            $table->string('email')->nullable()->unique()->comment('邮箱');
            $table->string('avatar')->comment('头像');
            $table->string('password')->comment('密码');
            $table->string('real_name')->nullable()->comment('真实姓名');
            $table->string('gender')->nullable()->comment('性别:male|female');
            $table->string('qq')->nullable()->comment('QQ');
            $table->string('wechat')->nullable()->comment('微信');
            $table->string('country_code')->nullable()->comment('国家|地区码');
            $table->string('phone')->nullable()->comment('手机');
            $table->string('facebook')->nullable()->comment('Facebook');
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
