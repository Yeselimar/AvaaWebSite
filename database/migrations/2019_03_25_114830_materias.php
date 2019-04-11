<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Materias extends Migration
{
    public function up()
    {
        Schema::create('materias', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('nombre');
            $table->float('nota');
            $table->unsignedInteger('periodo_id');
            $table->foreign('periodo_id')->references('id')->on('periodos')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('materias');
    }
}
