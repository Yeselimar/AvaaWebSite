<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Solicitudes extends Migration
{
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table)
        {
            $table->increments('id');
            $table->enum('titulo',['desincorporacion temporal','desincorporacion definitiva','reincorporacion','retroactivo','otros'])->default('otros');
            $table->text('descripcion');
            $table->text('observacion')->nullable();
            $table->enum('status',['enviada','aceptada','rechazada','cancelada'])->default('enviada');
            $table->unsignedInteger('usuario_respuesta')->nullable();
            $table->datetime('fecha_desincorporacion')->nullable();
            $table->datetime('fecha_inactividad')->nullable();
            $table->boolean('oculto_admin')->default(0);
            $table->boolean('oculto_usuario')->default(0);
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); //si se borra el registro de user se borra toda la relacion de la tabla alertas

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('solicitudes');
    }
}
