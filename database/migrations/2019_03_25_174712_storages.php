<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Storages extends Migration
{
    public function up()
    {
        Schema::create('storages', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('url');
            $table->boolean('in_noticia');

            $table->unsignedInteger('user_id');
            $table->unsignedInteger('noticia_id')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('storages');
    }
}
