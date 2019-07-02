<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParamValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('param_values', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('param_id')->nullable(false)->comment('param-id');
            $table->foreign('param_id')->references('id')->on('params')->onDelete('cascade');

            $table->string('value')->nullable(false)->comment('商品参数值');

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
        Schema::dropIfExists('param_values');
    }
}
