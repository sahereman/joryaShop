<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExchangeRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // unique-key: currency
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('currency')->nullable(false)->comment('currency-type[币种]:CNY|USD|etc')->unique();
            $table->unsignedDecimal('rate')->nullable(false)->comment('exchange-rate-to-CNY-1.00-￥');
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
        Schema::dropIfExists('exchange_rates');
    }
}
