<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Aval extends Migration
{
    public function up()
    {
        Schema::create('aval', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('url');
            $table->enum('estatus',['pendiente','aceptada','negada','devuelto'])->default('pendiente');
            $table->enum('tipo',['constancia','nota','justificacion','comprobante'])->default('constancia');
            //constancia para periodos, justificacion para actividades, comprobante para voluntariados, nota para cursos.
            $table->enum('extension',['pdf','imagen'])->default('imagen');
            $table->unsignedInteger('becario_id');
            $table->text('observacion');
            $table->foreign('becario_id')->references('user_id')->on('becarios')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aval');
    }
}
