<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable(false)->comment('user-id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('name')->nullable(false)->comment('收货人');
            // $table->string('country_code')->nullable()->comment('国家|地区码');
            $table->string('phone')->nullable(false)->comment('手机号码');
            $table->string('address')->nullable(false)->comment('详细地址');

            $table->boolean('is_default')->nullable(false)->default(false)->comment('是否为默认地址');
            $table->timestamp('last_used_at')->nullable()->comment('上次使用时间');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
}
