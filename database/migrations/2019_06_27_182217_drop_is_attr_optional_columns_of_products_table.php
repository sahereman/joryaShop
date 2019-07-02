<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropIsAttrOptionalColumnsOfProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_base_size_optional', 'is_hair_colour_optional', 'is_hair_density_optional']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_base_size_optional')->nullable(false)->default(true)->comment('SKU 参数 base_size 是否可选')->after('photos');
            $table->boolean('is_hair_colour_optional')->nullable(false)->default(true)->comment('SKU 参数 hair_colour 是否可选')->after('is_base_size_optional');
            $table->boolean('is_hair_density_optional')->nullable(false)->default(true)->comment('SKU 参数 hair_density 是否可选')->after('is_hair_colour_optional');
        });
    }
}
