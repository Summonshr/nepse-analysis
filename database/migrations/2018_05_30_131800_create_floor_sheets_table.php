<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFloorSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('floor_sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sn');
            $table->string('contract_no');
            $table->string('stock_symbol');
            $table->string('seller_broker');
            $table->string('buyer_broker');
            $table->string('quantity');
            $table->string('rate');
            $table->string('amount');
            $table->softDeletes();
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
        Schema::dropIfExists('floor_sheets');
    }
}
