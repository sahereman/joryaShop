<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountryProvinceCityColumnsToUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('title')->nullable()->default('')->comment('alias or title of this user address')->after('user_id');
            $table->string('surname')->nullable()->default('')->comment('surname or last name')->after('name');
            $table->string('country')->nullable()->default('')->comment('country or region')->after('phone');
            $table->string('province')->nullable()->default('')->comment('state or province or region')->after('country');
            $table->string('city')->nullable()->default('')->comment('city')->after('province');
            $table->string('backup_address')->nullable()->default('')->comment('optional backup address')->after('address');
            $table->string('zip')->nullable()->default('')->comment('zip code')->after('backup_address');
            $table->string('email')->nullable()->default('')->comment('email')->after('zip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('surname');
            $table->dropColumn('country');
            $table->dropColumn('province');
            $table->dropColumn('city');
            $table->dropColumn('backup_address');
            $table->dropColumn('zip');
            $table->dropColumn('email');
        });
    }
}
