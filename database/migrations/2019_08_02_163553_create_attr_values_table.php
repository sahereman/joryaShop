<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttrValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attr_values', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('attr_id')->nullable(false)->comment('attr-id');
            $table->foreign('attr_id')->references('id')->on('attrs')->onDelete('cascade');

            $table->string('value')->nullable(false)->comment('SKU 属性值');

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
        Schema::dropIfExists('attr_values');
    }
}
