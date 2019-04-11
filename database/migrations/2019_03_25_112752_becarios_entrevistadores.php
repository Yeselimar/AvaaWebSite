<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BecariosEntrevistadores extends Migration
{
    public function up()
    {
        Schema::create('becarios_entrevistadores', function (Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('becario_id');
            $table->foreign('becario_id')->references('user_id')->on('becarios')->onDelete('cascade');
            $table->unsignedInteger('entrevistador_id');
            $table->foreign('entrevistador_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('documento')->nullable();

            $table->boolean('oculto')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('becarios_entrevistadores');
    }
}
