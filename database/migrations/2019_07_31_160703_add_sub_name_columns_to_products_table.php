<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubNameColumnsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sub_name_en')->nullable()->comment('副标题')->after('name_zh');
            $table->string('sub_name_zh')->nullable()->comment('副标题')->after('sub_name_en');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sub_name_en');
            $table->dropColumn('sub_name_zh');
        });
    }
}
