<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution_levels', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedTinyInteger('level')->comment('分销级别');
            $table->unsignedDecimal('profit_ratio', 4, 2)->defaule(0)->comment('利润百分比');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('distribution_levels');
    }
}
