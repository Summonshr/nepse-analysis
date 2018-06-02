<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiveStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('time');
            $table->string('sn');
            $table->string('symbol');
            $table->string('ltp');
            $table->string('ltv');
            $table->string('point_change');
            $table->string('percentage_change');
            $table->string('open');
            $table->string('high');
            $table->string('low');
            $table->string('volume');
            $table->string('previous_closing');
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
        Schema::dropIfExists('live_stocks');
    }
}
