<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Mentores extends Migration
{
    public function up()
    {
        Schema::create('mentores', function (Blueprint $table)
        {
            $table->primary('user_id'); //se coloca el mismo id porque la relacion uno a uno no pueden existir dos perfiles con el mismo user
            $table->enum('status',['postulante','rechazado','activo','inactivo','desincorporado'])->default('activo');
            $table->text('profesion')->nullable();
            $table->text('empresa')->nullable();
            $table->text('cargo_actual')->nullable();
            $table->text('areas_de_interes')->nullable();
            $table->datetime('fecha_ingreso_empresa')->nullable();
            $table->datetime('fecha_inactivo')->nullable();
            $table->datetime('fecha_desincorporado')->nullable();
            $table->text('observacion_inactivo')->nullable();
            $table->text('observacion_desincorporado')->nullable();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mentores');
    }
}
