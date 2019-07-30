<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_templates', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('sub_name')->nullable();
            $table->string('description')->nullable();

            $table->unsignedInteger('from_country_id')->nullable()->comment('发货国-id');
            $table->foreign('from_country_id')->references('id')->on('country_provinces')->onDelete('set null');

            $table->unsignedTinyInteger('min_days')->comment('最快到达天数');
            $table->unsignedTinyInteger('max_days')->comment('最晚到达天数');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_templates');
    }
}
