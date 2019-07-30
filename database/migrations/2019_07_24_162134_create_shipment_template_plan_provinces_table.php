<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentTemplatePlanProvincesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_template_plan_provinces', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('plan_id');
            $table->foreign('plan_id')->references('id')->on('shipment_template_plans')->onDelete('cascade');

            $table->unsignedInteger('country_province_id');
            $table->foreign('country_province_id')->references('id')->on('country_provinces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_template_plan_provinces');
    }
}
