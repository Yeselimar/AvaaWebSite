<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Alertas extends Migration
{
    public function up()
    {
        Schema::create('alertas', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('titulo');
            $table->text('descripcion');
            $table->boolean('leido')->default(false);
            $table->enum('nivel',['bajo','medio','alto'])->default('bajo');
            $table->enum('status',['enviada','generada'])->default('enviada');
            $table->unsignedInteger('solicitud')->nullable();
            $table->unsignedInteger('actividad')->nullable();
            $table->unsignedInteger('cva')->nullable();
            $table->unsignedInteger('voluntariado')->nullable();
            $table->unsignedInteger('periodo')->nullable();
            $table->unsignedInteger('entrevista')->nullable();
            $table->enum('tipo',['nomina','solicitud','relacionbm','justificativo','actividad','cva','voluntariado','periodo','entrevista','otros'])->nullable()->default('solicitud');
            $table->boolean('oculto')->default(0);
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); //si se borra el registro de user se borra toda la relacion de la tabla alertas

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alertas');
    }
}
