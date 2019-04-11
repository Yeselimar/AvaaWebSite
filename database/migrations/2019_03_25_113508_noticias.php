<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Noticias extends Migration
{
    public function up()
    {
        Schema::create('noticias', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('titulo');
            $table->string('slug');
            $table->longText('contenido');
            $table->string('url_imagen');
            $table->enum('tipo',['noticia','miembroins'])->default('noticia');
            $table->string('informacion_contacto');
            $table->string('url_articulo')->nullable();
            $table->string('email_contacto')->nullable();
            $table->string('telefono_contacto')->nullable();

            $table->boolean('al_carrousel')->default(1);

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('noticias');
    }
}
