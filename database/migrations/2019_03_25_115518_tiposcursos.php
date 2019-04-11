<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Tiposcursos extends Migration
{
    public function up()
    {
        Schema::create('tiposcursos', function (Blueprint $table)
        {
            $table->increments('id');

            $table->text('nombre');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tiposcursos');
    }
}
