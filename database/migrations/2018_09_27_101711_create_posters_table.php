<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostersTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('posters', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable()->comment('name-in-Chinese:仅用于后台管理展示');
            $table->string('slug')->nullable()->unique()->comment('调用使用时的标示位');
            $table->string('disk')->nullable()->comment('image-filesystem-disk:local|public|cloud');
            $table->string('image')->nullable(false)->comment('image-path');
            $table->string('link')->nullable(false)->default('')->comment('link-url:eg.%poster-link-url%?page_id=1');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posters');
    }
}
