<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BecariosNomina extends Migration
{
    public function up()
    {
        Schema::create('becarios_nominas',function(Blueprint $table)
        {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('becarios')->onDelete('cascade');

            $table->unsignedInteger('nomina_id');
            $table->foreign('nomina_id')->references('id')->on('nominas')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('becarios_nominas');
    }
}
