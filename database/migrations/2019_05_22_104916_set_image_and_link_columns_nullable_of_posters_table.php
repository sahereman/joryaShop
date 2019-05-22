<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetImageAndLinkColumnsNullableOfPostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posters', function (Blueprint $table) {
            $table->string('image')->nullable()->default('')->comment('image-path')->change();
            $table->string('link')->nullable()->default('')->comment('link-url:eg.%poster-link-url%?page_id=1')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posters', function (Blueprint $table) {
            $table->string('image')->nullable(false)->default('')->comment('image-path')->change();
            $table->string('link')->nullable(false)->default('')->comment('link-url:eg.%poster-link-url%?page_id=1')->change();
        });
    }
}
