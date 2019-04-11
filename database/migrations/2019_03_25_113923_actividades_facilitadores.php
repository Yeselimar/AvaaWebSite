<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActividadesFacilitadores extends Migration
{
    public function up()
    {
        Schema::create('actividades_facilitadores', function (Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('actividad_id');
            $table->foreign('actividad_id')->references('id')->on('actividades')->onDelete('cascade');

            $table->unsignedInteger('becario_id')->nullable();
            $table->foreign('becario_id')->references('user_id')->on('becarios')->onDelete('cascade');

            $table->string('nombreyapellido')->nullable();

            $table->integer('horas')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('actividades_facilitadores');
    }
}
