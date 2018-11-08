<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable(false)->comment('编码');
            $table->string('name')->nullable(false)->comment('公司名称');
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
        Schema::dropIfExists('shipment_companies');
    }
}
