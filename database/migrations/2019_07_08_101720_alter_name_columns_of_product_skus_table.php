<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNameColumnsOfProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->string('name_en')->nullable()->default('')->comment('英文名称')->change(); // 备用字段
            $table->string('name_zh')->nullable()->default('')->comment('中文名称')->change(); // 备用字段
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->string('name_en')->nullable(false)->default('')->comment('英文名称')->change(); // 备用字段
            $table->string('name_zh')->nullable(false)->default('')->comment('中文名称')->change(); // 备用字段
        });
    }
}
