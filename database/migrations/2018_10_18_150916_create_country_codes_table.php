<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountryCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_codes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('country_name')->nullable(false)->comment('country|area name');
            // $table->string('country_name_zh')->nullable(false)->comment('国家|地区名称');
            $table->string('country_iso')->nullable(false)->comment('国家|地区代号');
            $table->string('country_code')->nullable(false)->comment('国家|地区码');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_codes');
    }
}
