<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Charlas extends Migration
{
    public function up()
    {
        Schema::create('charlas', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('anho');
            $table->string('imagen');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charlas');
    }
}
