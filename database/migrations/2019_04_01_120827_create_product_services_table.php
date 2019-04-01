<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_services', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable(false)->comment('服务名称');
            $table->string('description')->nullable(false)->comment('服务描述');
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
        Schema::dropIfExists('product_services');
    }
}
