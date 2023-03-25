<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMilitaryScanTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('military_scans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('scan_id');
            $table->integer('ship_id');
            $table->bigInteger('base')->default(0);
            $table->bigInteger('f1')->default(0);
            $table->bigInteger('f2')->default(0);
            $table->bigInteger('f3')->default(0);
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
        Schema::dropIfExists('military_scans');
    }
}
