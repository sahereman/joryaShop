<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');

            $table->string('type')->nullable(false)->comment('banner-type:index|etc');
            $table->string('disk')->nullable()->comment('image-filesystem-disk:local|public|cloud');
            $table->string('image')->nullable(false)->comment('image-path');

            $table->unsignedSmallInteger('sort')->nullable(false)->default(0)->comment('排序值');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
