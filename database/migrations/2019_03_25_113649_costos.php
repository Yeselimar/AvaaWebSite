<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Costos extends Migration
{
    public function up()
    {
        Schema::create('costos', function (Blueprint $table)
        {
            $table->increments('id');
            $table->double('sueldo_becario',20,2)->default(0);
            $table->double('costo_ases_basica',20,2)->default(0);
            $table->double('costo_ases_intermedia',20,2)->default(0);
            $table->double('costo_ases_completa',20,2)->default(0);
            $table->double('costo_membresia',20,2)->default(0);
            $table->double('costo_adicional1',20,2)->default(0);
            $table->datetime('fecha_valido')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('costos');
    }
}
