<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSharePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('share_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sn');
            $table->string('company');
            $table->string('date');
            $table->string('no_of_transaction');
            $table->string('max_price');
            $table->string('min_price');
            $table->string('closing_price');
            $table->string('traded_shares');
            $table->string('amount');
            $table->string('previous_closing');
            $table->string('difference');
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
        Schema::dropIfExists('share_prices');
    }
}
