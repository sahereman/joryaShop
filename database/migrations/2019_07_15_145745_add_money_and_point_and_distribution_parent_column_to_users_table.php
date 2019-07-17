<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoneyAndPointAndDistributionParentColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('distribution_parent')->nullable()->default(null)->comment('分销父级的用户ID');
            $table->foreign('distribution_parent')->references('id')->on('users')->onDelete('set null');

            $table->unsignedDecimal('money', 8, 2)->default(0)->comment('余额');
            $table->unsignedDecimal('point', 8, 2)->default(0)->comment('积分');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['distribution_parent']);
            $table->dropColumn('distribution_parent');
            $table->dropColumn('money');
            $table->dropColumn('point');
        });
    }
}
