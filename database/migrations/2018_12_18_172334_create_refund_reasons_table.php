<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reason_en')->nullable(false)->comment('原因英文');
            $table->string('reason_zh')->nullable(false)->comment('原因中文');
            $table->unsignedSmallInteger('sort')->nullable(false)->default(0)->comment('排序值');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund_reasons');
    }
}
