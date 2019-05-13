<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSeoColumnsToProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            /* 2019-04-09 for SEO */
            $table->string('seo_title')->nullable()->comment('title-for-seo')->after('content_zh');
            $table->string('seo_keywords')->nullable()->comment('keywords-for-seo')->after('seo_title');
            $table->string('seo_description')->nullable()->comment('description-for-seo')->after('seo_keywords');
            /* 2019-04-09 for SEO */
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('product_categories', function (Blueprint $table) {
            /* 2019-04-09 for SEO */
            $table->dropColumn('seo_title');
            $table->dropColumn('seo_keywords');
            $table->dropColumn('seo_description');
            /* 2019-04-09 for SEO */
        });
    }
}
