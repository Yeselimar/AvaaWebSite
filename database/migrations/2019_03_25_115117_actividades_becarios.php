<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActividadesBecarios extends Migration
{
    public function up()
    {
        Schema::create('actividades_becarios',function(Blueprint $table)
        {
            $table->increments('id');

            $table->unsignedInteger('becario_id');
            $table->foreign('becario_id')->references('user_id')->on('becarios')->onDelete('cascade');

            $table->unsignedInteger('actividad_id');
            $table->foreign('actividad_id')->references('id')->on('actividades')->onDelete('cascade');

            $table->unsignedInteger('aval_id')->nullable();
            $table->foreign('aval_id')->references('id')->on('aval');

            $table->enum('estatus',['asistira','lista de espera','justificacion cargada','asistio','no asistio'])->default('asistira');

            $table->timestamps();//fechainscripcion
        });
    }

    public function down()
    {
        Schema::dropIfExists('actividades_becarios');
    }
}
