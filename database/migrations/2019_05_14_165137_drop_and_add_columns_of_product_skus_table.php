<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropAndAddColumnsOfProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::table('product_skus', function (Blueprint $table) {
            // 2019-05-14
            $table->dropColumn(['base_size_en', 'base_size_zh', 'hair_colour_en', 'hair_colour_zh', 'hair_density_en', 'hair_density_zh']);
            $table->json('attributes')->nullable()->comment('product-sku-attributes-in-json-format')->after('name_zh');
            // 2019-05-14
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('product_skus', function (Blueprint $table) {
            // 2019-05-14
            $table->dropColumn('attributes');
            $table->string('base_size_en')->nullable()->default('Common')->comment('Base Size 英文名称')->after('name_zh');
            $table->string('base_size_zh')->nullable()->default('普通')->comment('Base Size 中文名称')->after('base_size_en');
            $table->string('hair_colour_en')->nullable()->default('Common')->comment('Hair Colour 英文名称')->after('base_size_zh');
            $table->string('hair_colour_zh')->nullable()->default('普通')->comment('Hair Colour 中文名称')->after('hair_colour_en');
            $table->string('hair_density_en')->nullable()->default('Common')->comment('Hair Density 英文名称')->after('hair_colour_zh');
            $table->string('hair_density_zh')->nullable()->default('普通')->comment('Hair Density 中文名称')->after('hair_density_en');
            // 2019-05-14
        });*/
    }
}
