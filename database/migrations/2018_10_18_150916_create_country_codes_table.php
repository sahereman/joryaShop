<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryCodesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('country_codes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('country_name')->nullable(false)->comment('country|area name');
            // $table->string('country_name_zh')->nullable(false)->comment('国家|地区名称');
            $table->string('country_iso')->nullable(false)->comment('国家|地区代号'); // 备用字段
            $table->string('country_code')->nullable(false)->comment('国家|地区码');
            $table->unsignedSmallInteger('sort')->nullable(false)->default(0)->comment('排序值');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_codes');
    }
}
