<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Mensajes extends Migration
{
    public function up()
    {
        Schema::create('mensajes', function (Blueprint $table)
        {
            $table->increments('id');

            $table->string('asunto');
            $table->text('descripcion');
            $table->text('categoria');//no se usará por los momentos
            $table->boolean('favorito');
            $table->boolean('leido')->default('0');

            $table->enum('estatus',['entrada', 'borrado', 'eliminado'])->default('entrada');

            $table->unsignedInteger('emisor_id')->nullable();
            $table->foreign('emisor_id')->references('id')->on('users')->onDelete('cascade'); //no se usará por los momentos

            $table->unsignedInteger('receptor_id')->nullable();
            $table->foreign('receptor_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mensajes');
    }
}
