<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        // unique-key: currency
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->nullable()->comment('仅用于后台管理展示');
            $table->string('currency')->nullable(false)->comment('currency-type[币种]:USD|CNY|etc')->unique();
            $table->unsignedDecimal('rate', 8, 2)->nullable(false)->comment('exchange-rate-of-USD');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exchange_rates');
    }
}
