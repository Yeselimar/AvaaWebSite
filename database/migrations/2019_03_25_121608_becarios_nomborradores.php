<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BecariosNomborradores extends Migration
{
    public function up()
    {
        Schema::create('becarios_nomborradores',function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('becarios')->onDelete('cascade');

            $table->unsignedInteger('nomborrador_id');
            $table->foreign('nomborrador_id')->references('id')->on('nomborradores')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('becarios_nomborradores');
    }
}
