<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAbbrAndPhotoColumnsToProductSkuAttrValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_sku_attr_values', function (Blueprint $table) {
            $table->string('abbr')->nullable()->comment('abbreviation')->after('value');
            $table->string('photo')->nullable()->comment('图片')->after('abbr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_sku_attr_values', function (Blueprint $table) {
            $table->dropColumn('photo', 'abbr');
        });
    }
}
