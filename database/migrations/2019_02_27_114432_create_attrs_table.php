<?php

/*商品属性 2019-03-01*/
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttrsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        /*Schema::create('attrs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable(false)->default(0)->comment('parent-attr-id:用于商品属性 名称-值 展示');

            $table->string('name_en')->nullable(false)->comment('英文名称');
            $table->string('name_zh')->nullable(false)->comment('中文名称');

            $table->unsignedSmallInteger('sort')->nullable(false)->default(0)->comment('排序值');

            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('attrs');
    }
}
/*商品属性 2019-03-01*/
