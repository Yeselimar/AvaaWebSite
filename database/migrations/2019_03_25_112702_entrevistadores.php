<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Entrevistadores extends Migration
{
    public function up()
    {
        Schema::create('entrevistadores', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('nombre_apellido');
            $table->unsignedInteger('cedula');
            $table->string('correo')->nullable();
            $table->string('telefono')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entrevistadores');
    }
}
