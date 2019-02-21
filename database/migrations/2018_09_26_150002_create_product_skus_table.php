<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_skus', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('product_id')->nullable(false)->comment('product-id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->string('name_en')->nullable()->default('')->comment('英文名称'); // 备用字段
            $table->string('name_zh')->nullable(false)->comment('中文名称');

            // 2019-01-22
            $table->string('base_size_en')->nullable()->default('Common')->comment('Base Size 英文名称');
            $table->string('base_size_zh')->nullable()->default('普通')->comment('Base Size 中文名称');
            $table->string('hair_colour_en')->nullable()->default('Common')->comment('Hair Colour 英文名称');
            $table->string('hair_colour_zh')->nullable()->default('普通')->comment('Hair Colour 中文名称');
            $table->string('hair_density_en')->nullable()->default('Common')->comment('Hair Density 英文名称');
            $table->string('hair_density_zh')->nullable()->default('普通')->comment('Hair Density 中文名称');
            // 2019-01-22

            $table->string('photo')->nullable()->comment('单一图片'); // 备用字段

            $table->unsignedDecimal('price', 8, 2)->nullable(false)->comment('价格');
            $table->unsignedInteger('stock')->nullable(false)->default(0)->comment('库存');
            $table->unsignedInteger('sales')->nullable(false)->default(0)->comment('销量');

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
        Schema::dropIfExists('product_skus');
    }
}
