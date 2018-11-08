<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name_en')->nullable()->comment('英文名称');
            $table->string('name_zh')->nullable()->comment('中文名称');
            $table->string('slug')->nullable()->comment('调用使用时的标示位:pc|mobile');
            $table->string('icon')->nullable(false)->comment('icon-path');
            $table->string('link')->nullable(false)->comment('promotion-link-url');
            $table->unsignedSmallInteger('sort')->nullable(false)->default(0)->comment('排序值');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
