<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table)
        {
            $table->increments('id');
            $table->boolean('estatus')->default(1);
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->enum('sexo',['femenino','masculino'])->nullable();
            $table->unsignedInteger('edad')->nullable();
            $table->string('email',30)->unique();
            $table->string('password');
            $table->enum('rol',['rechazado','admin','coordinador','mentor','postulante_becario','postulante_mentor','pre_postulante_mentor','becario','editor','directivo','mentor desincorporado','becario desincorporado','entrevistador','soporte'])->default('postulante_becario');//el rol rechazado puede eliminarse.
            $table->string('cedula',15)->nullable()->unique();
            $table->string('descripcion')->nullable();
            $table->timestamp('fecha_nacimiento')->nullable();
            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
