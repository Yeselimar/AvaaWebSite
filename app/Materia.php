<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $table='materias';

    public function periodo()//relación buena
    {
        return $this->belongsTo('avaa\Periodo','periodo_id');
    }
}
