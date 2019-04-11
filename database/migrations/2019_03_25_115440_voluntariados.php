<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Voluntariados extends Migration
{
    public function up()
    {
        Schema::create('voluntariados',function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('nombre');//se puede obviar y dejar solo instituto
            $table->string('institucion');
            $table->string('responsable'); // tambien llamado persona de contacto
            $table->text('observacion')->nullable();
            $table->datetime('fecha');
            $table->enum('tipo',['interno','externo'])->default('interno');
            $table->string('lugar')->nullable();//en caso de que el voluntariado externo
            $table->unsignedInteger('horas');//horas del voluntariado

            $table->unsignedInteger('becario_id');
            $table->foreign('becario_id')->references('user_id')->on('becarios')->onDelete('cascade');

            $table->unsignedInteger('aval_id');
            $table->foreign('aval_id')->references('id')->on('aval')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('voluntariados');
    }
}
