<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Concursos extends Migration
{
    public function up()
    {
        Schema::create('concursos', function (Blueprint $table)
        {
            $table->increments('id');
            $table->datetime('fecha_inicio');
            $table->datetime('fecha_final');
            $table->enum('tipo',['becarios', 'mentores'])->default('becarios');
            $table->enum('status',['abierto','cerrado','finalizado']);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('concursos');
    }
}
