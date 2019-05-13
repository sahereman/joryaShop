<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInputColumnOfAdminOperationLogTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table(config('admin.database.operation_log_table'), function (Blueprint $table) {
            $table->mediumText('input')->nullable()->comment('input-content')->change();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table(config('admin.database.operation_log_table'), function (Blueprint $table) {
            $table->text('input')->change();
        });
    }
}
