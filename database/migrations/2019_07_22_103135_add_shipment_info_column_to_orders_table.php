<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShipmentInfoColumnToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->json('shipment_info')->nullable()->comment('order_shipment_traces')->after('shipment_sn'); // 物流信息
            $table->timestamp('last_queried_at')->nullable()->comment('上次查询物流信息时间')->after('shipment_info');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('last_queried_at');
            $table->dropColumn('shipment_info');
        });
    }
}
