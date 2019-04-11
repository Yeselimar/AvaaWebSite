<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cursos extends Migration
{
    public function up()
    {
        Schema::create('cursos',function(Blueprint $table)
        {
            $table->increments('id');
            $table->enum('instituto',['Centro Venezolano Americano']);// disabled

            $table->enum('nivel',['basico','intermedio','avanzado'])->default('basico');
            $table->unsignedInteger('modulo');//modulo de cva (1...18)
            $table->enum('modo',['sabatino','interdiario','diario','intensivo'])->default('sabatino');//modo cva
            $table->datetime('fecha_inicio');//fecha inicio del cva
            $table->datetime('fecha_fin');//fecha fin del cva
            $table->float('nota');//nota en ese modulo
            $table->enum('status',['aprobado','reprobado'])->default('reprobado');//verificar si este campo se mantiene

            $table->unsignedInteger('becario_id');
            $table->foreign('becario_id')->references('user_id')->on('becarios')->onDelete('cascade');

            $table->unsignedInteger('tipocurso_id');
            $table->foreign('tipocurso_id')->references('id')->on('tiposcursos')->onDelete('cascade');

            $table->unsignedInteger('aval_id')->nullable();
            $table->foreign('aval_id')->references('id')->on('aval')->onDelete('cascade');

            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('cursos');
    }
}
