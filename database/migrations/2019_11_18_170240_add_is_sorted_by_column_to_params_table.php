<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsSortedByColumnToParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('params', function (Blueprint $table) {
            $table->boolean('is_sorted_by')->nullable(false)->default(true)->comment('是否用于 Sort By')->after('sort');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('params', function (Blueprint $table) {
            $table->dropColumn('is_sorted_by');
        });
    }
}
