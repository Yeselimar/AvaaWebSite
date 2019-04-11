<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Contactos extends Migration
{
    public function up()
    {
        Schema::create('contactos', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('nombre_completo');
            $table->string('telefono');
            $table->string('correo');
            $table->string('asunto');
            $table->text('mensaje');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contactos');
    }
}
