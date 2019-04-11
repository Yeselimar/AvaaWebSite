<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Desincorporaciones extends Migration
{
    public function up()
    {
        Schema::create('desincorporaciones', function (Blueprint $table)
        {
            $table->increments('id');
            $table->enum('tipo',['sistema','solicitud'])->default('sistema');
            $table->enum('status',['ejecutada','sin ejecutar'])->default('sin ejecutar');
            $table->text('observacion')->nullable();
            $table->unsignedInteger('gestionada_por')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('datos_nombres')->nullable();
            $table->string('datos_apellidos')->nullable();
            $table->string('datos_cedula')->nullable();
            $table->string('datos_email')->nullable();
            $table->string('datos_rol')->nullable();
            $table->datetime('fecha_gestionada')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('desincorporaciones');
    }
}
