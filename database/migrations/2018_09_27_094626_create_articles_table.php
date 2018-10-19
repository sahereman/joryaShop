<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // unique-key: name
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable()->comment('name-in-Chinese:仅用于后台管理展示');
            $table->string('slug')->nullable()->comment('调用使用时的标示位');
            $table->string('content_en')->nullable()->comment('page-content-in-English');
            $table->string('content_zh')->nullable()->comment('page-content-in-Chinese');

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
        Schema::dropIfExists('articles');
    }
}
