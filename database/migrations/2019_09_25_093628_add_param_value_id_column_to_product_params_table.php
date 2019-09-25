<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParamValueIdColumnToProductParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_params', function (Blueprint $table) {
            $table->unsignedInteger('param_value_id')->nullable()->comment('param-value-id')->after('product_id');
            $table->foreign('param_value_id')->references('id')->on('param_values')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_params', function (Blueprint $table) {
            $table->dropForeign('product_params_param_value_id_foreign');
            // $table->dropForeign(['param_value_id']);
            $table->dropColumn('param_value_id');
        });
    }
}
