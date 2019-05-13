<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContentColumnOfArticlesTablePlus extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->mediumText('content_en')->nullable()->comment('article-content-in-English')->change();
            $table->mediumText('content_zh')->nullable()->comment('article-content-in-Chinese')->change();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->text('content_en')->change();
            $table->text('content_zh')->change();
        });
    }
}
