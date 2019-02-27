<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContentColumnToProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->text('content_en')->nullable()->comment('英文内容')->after('description_zh');
            $table->text('content_zh')->nullable()->comment('中文内容')->after('content_en');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['content_en', 'content_zh']);
        });
    }
}
