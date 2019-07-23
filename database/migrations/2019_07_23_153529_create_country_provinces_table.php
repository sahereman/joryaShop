<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_provinces', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('parent_id')->nullable(false)->default(0);

            $table->string('type')->nullable();

            $table->string('name_zh')->nullable()->comment('中文名称');
            $table->string('name_en')->nullable()->comment('英文名称');

            $table->string('code')->nullable();

            $table->unsignedSmallInteger('sort')->nullable(false)->default(0)->comment('排序值');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_provinces');
    }
}
