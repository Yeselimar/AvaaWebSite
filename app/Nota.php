<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table='notas';

    public function curso()
    {
        return $this->belongsTo('avaa\Curso','curso_id');
    }

    public function becario()
    {
        return $this->belongsTo('avaa\Becario','becario_id','user_id');
    }
}
