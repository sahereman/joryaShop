<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentIdToMenusTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->unsignedInteger('parent_id')->nullable(false)->default(0)->comment('parent-menu-id')->after('id');
            $table->string('link')->nullable()->default('')->comment('promotion-link-url')->change();

            $table->string('name_en')->nullable()->comment('英文名称')->change();
            $table->string('name_zh')->nullable()->comment('中文名称')->change();
            $table->string('slug')->nullable()->comment('调用使用时的标示位:pc|mobile')->change();
            $table->string('link')->nullable()->default('')->comment('promotion-link-url')->change();
            $table->unsignedSmallInteger('sort')->nullable()->default(0)->comment('排序值')->change();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->string('link')->nullable(false)->default('')->comment('promotion-link-url')->change();

            $table->string('name_en')->nullable(false)->comment('英文名称')->change();
            $table->string('name_zh')->nullable(false)->comment('中文名称')->change();
            $table->string('slug')->nullable(false)->comment('调用使用时的标示位:pc|mobile')->change();
            $table->string('link')->nullable(false)->default('')->comment('promotion-link-url')->change();
            $table->unsignedSmallInteger('sort')->nullable(false)->default(0)->comment('排序值')->change();
        });
    }
}
