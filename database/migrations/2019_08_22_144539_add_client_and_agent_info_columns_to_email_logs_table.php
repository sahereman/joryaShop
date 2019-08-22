<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClientAndAgentInfoColumnsToEmailLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->string('name')->nullable()->comment('client name')->after('email');
            $table->string('phone')->nullable()->comment('client phone')->after('name');
            $table->string('address')->nullable()->comment('client address')->after('phone');
            $table->string('agent')->nullable()->comment('agent info')->after('address');
            $table->boolean('facebook')->nullable(false)->default(false)->comment('communicated via facebook')->after('agent');
            $table->timestamp('sent_at')->nullable()->comment('sent at')->after('facebook');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropColumn(['name', 'phone', 'address', 'agent', 'facebook', 'sent_at']);
        });
    }
}
