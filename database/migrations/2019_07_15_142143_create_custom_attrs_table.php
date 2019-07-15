<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomAttrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_attrs', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable(false)->comment('定制商品 SKU 属性名称');
            $table->index('name');

            $table->boolean('is_required')->nullable(false)->default(false)->comment('是否必填');

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
        Schema::dropIfExists('custom_attrs');
    }
}
