<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name_en')->nullable(false)->comment('英文名称');
            $table->string('name_zh')->nullable(false)->comment('中文名称');
            $table->string('slug')->nullable(false)->comment('调用使用时的标示位:pc|mobile');
            $table->string('icon')->nullable()->comment('icon-path'); // 备用字段
            $table->string('link')->nullable(false)->default('')->comment('promotion-link-url');
            $table->unsignedSmallInteger('sort')->nullable(false)->default(0)->comment('排序值');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
