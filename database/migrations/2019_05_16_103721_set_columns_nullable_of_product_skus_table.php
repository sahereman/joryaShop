<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetColumnsNullableOfProductSkusTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->string('name_zh')->nullable()->comment('中文名称')->change();
            $table->unsignedDecimal('price', 8, 2)->nullable()->comment('价格')->change();
            $table->unsignedInteger('stock')->nullable()->default(0)->comment('库存')->change();
            $table->unsignedInteger('sales')->nullable()->default(0)->comment('销量')->change();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('product_skus', function (Blueprint $table) {
            $table->string('name_zh')->nullable(false)->comment('中文名称')->change();
            $table->unsignedDecimal('price', 8, 2)->nullable(false)->comment('价格')->change();
            $table->unsignedInteger('stock')->nullable(false)->default(0)->comment('库存')->change();
            $table->unsignedInteger('sales')->nullable(false)->default(0)->comment('销量')->change();
        });
    }
}
