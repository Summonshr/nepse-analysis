<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubIndicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_indices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('SN');
            $table->string('date');
            $table->string('sub_index');
            $table->string('index_type');
            $table->string('absolute_change');
            $table->string('percentage_change');
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
        Schema::dropIfExists('sub_indices');
    }
}
