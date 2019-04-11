<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Instituciones extends Migration
{
    public function up()
    {
        Schema::create('instituciones', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('nombre');
            $table->string('abreviatura');
            $table->text('descripcion');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('instituciones');
    }
}
