<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Actividades extends Migration
{
    public function up()
    {
        Schema::create('actividades', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('nombre');
            $table->string('anho_academico')->nullable();
            $table->enum('tipo',['taller','chat club'])->default('taller');
            $table->enum('nivel',['basico','intermedio','avanzado','cualquier nivel'])->default('basico');
            $table->enum('modalidad',['presencial','virtual'])->default('presencial');
            $table->text('descripcion');
            $table->unsignedInteger('limite_participantes');
            $table->unsignedInteger('horas_voluntariado');//horas en voluntariado
            $table->datetime('fecha')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->enum('status',['suspendido','oculto','disponible','cerrado'])->default('disponible');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('actividades');
    }
}
