<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Notas extends Migration
{
    public function up()
    {
        Schema::create('notas', function (Blueprint $table)
        {
            $table->increments('id');
            $table->decimal('promedio');
            $table->enum('status',['aprobado','reprobado'])->default('aprobado');

            $table->unsignedInteger('curso_id');
            $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');

            $table->unsignedInteger('becario_id');
            $table->foreign('becario_id')->references('user_id')->on('becarios')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notas');
    }
}
