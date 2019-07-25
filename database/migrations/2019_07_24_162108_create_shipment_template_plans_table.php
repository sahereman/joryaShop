<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentTemplatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_template_plans', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('shipment_template_id');
            $table->foreign('shipment_template_id')->references('id')->on('shipment_templates')->onDelete('cascade');

            $table->unsignedSmallInteger('base_unit')->default(1)->comment('首件单位以内');
            $table->unsignedDecimal('base_price')->default(1)->comment('首件运费');

            $table->unsignedDecimal('join_price')->default(1)->comment('续件运费');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_template_plans');
    }
}
