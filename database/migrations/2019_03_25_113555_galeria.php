<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Galeria extends Migration
{
    public function up()
    {
        Schema::create('galeria', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('imagen');

            $table->unsignedInteger('noticia_id');
            $table->foreign('noticia_id')->references('id')->on('noticias')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('galeria');
    }
}
