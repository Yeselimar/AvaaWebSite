<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Periodos extends Migration
{
    public function up()
    {
        Schema::create('periodos', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('numero_periodo');//1er semestre: según el regimen del becario
            $table->string('regimen_periodo');//por si se cambia de regimen
            $table->string('anho_lectivo');//2-2018
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');

            $table->unsignedInteger('becario_id');
            $table->foreign('becario_id')->references('user_id')->on('becarios')->onDelete('cascade');

            $table->unsignedInteger('aval_id');//constancia de notas
            $table->foreign('aval_id')->references('id')->on('aval');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('periodos');
    }
}
