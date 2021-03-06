<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPaymentsUserIdForeignKeyOfPaymentsTableOnDeleteSetNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // $table->dropForeign(['user_id']);
            $table->dropForeign('payments_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // $table->dropForeign(['user_id']);
            $table->dropForeign('payments_user_id_foreign');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
