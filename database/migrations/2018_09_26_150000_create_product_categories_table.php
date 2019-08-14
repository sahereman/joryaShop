<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable(false)->default(0)->comment('parent-category-id:用于商品二级分类');

            $table->string('banner')->nullable()->comment('banner'); // 备用字段
            $table->string('name_en')->nullable(false)->comment('英文名称');
            $table->string('name_zh')->nullable(false)->comment('中文名称'); // 备用字段
            $table->string('description_en', 1000)->nullable()->comment('英文描述');
            $table->string('description_zh', 1000)->nullable()->comment('中文描述'); // 备用字段
            $table->boolean('is_index')->nullable(false)->default(false)->comment('是否允许在首页展示推荐商品');

            $table->unsignedSmallInteger('sort')->nullable(false)->default(0)->comment('排序值');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_categories');
    }
}
