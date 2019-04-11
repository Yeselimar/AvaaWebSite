<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Editores extends Migration
{
    public function up()
    {
        Schema::create('editores', function (Blueprint $table)
        {
            $table->primary('user_id'); //se coloca el mismo id porque la relacion uno a uno no pueden existir dos perfiles con el mismo user

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('editores');
    }
}
