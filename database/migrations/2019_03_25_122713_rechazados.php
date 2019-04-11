<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Rechazados extends Migration
{
    public function up()
    {
        Schema::create('rechazados', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('last_name');
            $table->enum('etapa',['antes','durante','despues'])->default('antes');
            $table->enum('datos',['borrados','sinborrar'])->default('sinborrar');
            $table->enum('visibilidad',['oculto','visible'])->default('visible');
            $table->string('intentos')->nullable();
            $table->string('telefono')->nullable();
            $table->datetime('fecha_de_participacion')->nullable();
            $table->string('cedula',15);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rechazados');
    }
}
