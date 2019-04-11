<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Nomborradores extends Migration
{
    public function up()
    {
        Schema::create('nomborradores', function (Blueprint $table)
        {
            $table->increments('id');
            $table->double('cva',20,2)->default(0);//según lo que dijo bapssy
            $table->double('retroactivo',20,2)->default(0);
            $table->double('sueldo_base',20,2)->default(0);
            $table->double('monto_libros',20,2)->default(0);
            $table->double('total',20,2)->default(0);
            $table->unsignedInteger('mes');
            $table->unsignedInteger('year');
            $table->enum('status',['pendiente', 'generado', 'pagado','anulado'])->default('pendiente');
            $table->string('datos_nombres')->nullable();
            $table->string('datos_apellidos')->nullable();
            $table->string('datos_cuenta')->nullable();
            $table->string('datos_email')->nullable();
            $table->string('datos_cedula')->nullable();
            $table->string('datos_status')->nullable();
            $table->timestamp('datos_fecha_ingreso')->nullable();
            $table->timestamp('datos_final_carga_academica')->nullable();
            $table->timestamp('datos_fecha_bienvenida')->nullable();
            $table->unsignedInteger('datos_id')->nullable();

            $table->timestamp('fecha_pago')->nullable();
            $table->timestamp('fecha_generada')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nomborradores');
    }
}
