<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecesoDecembrino extends Migration
{
    public function up()
    {
        Schema::create('receso_decembrino', function (Blueprint $table)
        {
            $table->increments('id');

            $table->datetime('fecha_inicio');
            $table->datetime('fecha_fin');
            $table->boolean('activo')->default('0');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('receso_decembrino');
    }
}
