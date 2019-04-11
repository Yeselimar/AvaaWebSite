<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tickets extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table)
        {
            $table->increments('id');

            $table->enum('estatus',['enviado','en revision','cerrado'])->default('enviado');
            $table->enum('prioridad',['baja','media','alta'])->default('baja');
            $table->enum('tipo',['soporte','ayuda'])->default('soporte');
            $table->string('asunto');
            $table->text('descripcion');
            $table->text('imagen')->nullable();
            $table->text('url')->nullable();
            $table->text('respuesta');
            $table->boolean('notificado')->default('0');
            $table->datetime('fecha_notificado')->nullable();

            $table->unsignedInteger('usuario_genero_id');
            $table->foreign('usuario_genero_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('usuario_respuesta_id')->nullable();
            $table->foreign('usuario_respuesta_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
