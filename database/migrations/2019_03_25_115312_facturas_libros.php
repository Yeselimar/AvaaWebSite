<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FacturasLibros extends Migration
{
    public function up()
    {
        Schema::create('factlibros', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('curso');
            $table->string('url');
            $table->double('costo',20,2);
            $table->datetime('fecha_pagada');
            $table->datetime('fecha_cargada');
            $table->datetime('fecha_procesada');
            $table->unsignedInteger('mes')->nullable();
            $table->unsignedInteger('year')->nullable();
            $table->enum('status',['cargada','por procesar','revisada','pagada','rechazada','procesada'])->default('cargada');

            $table->unsignedInteger('becario_id');
            $table->foreign('becario_id')->references('user_id')->on('becarios')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('factlibros');
    }
}
