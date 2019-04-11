<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Documentos extends Migration
{
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('titulo');
            $table->string('url');
            $table->boolean('verificado');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documentos');
    }
}
