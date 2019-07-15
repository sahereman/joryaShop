<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomAttrValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_attr_values', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('custom_attr_id')->nullable(false)->comment('custom-attr-id');
            $table->foreign('custom_attr_id')->references('id')->on('custom_attrs')->onDelete('cascade');

            $table->string('value')->nullable(false)->comment('订制商品 SKU 属性值');
            $table->index('value');

            $table->decimal('delta_price', 8, 2)->nullable(false)->default(0.00)->comment('加价[+|-]');

            $table->string('photo')->nullable()->comment('图片');

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
        Schema::dropIfExists('custom_attr_values');
    }
}
