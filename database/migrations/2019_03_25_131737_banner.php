<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Banner extends Migration
{
    public function up()
    {
        Schema::create('banner', function (Blueprint $table)
        {
            $table->increments('id');
            $table->enum('tipo',['banner','empresas','organizaciones','instituciones'])->default('banner');
            $table->string('titulo');
            $table->string('imagen');
            $table->string('url');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banner');
    }
}
