<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    protected $table='instituciones';

    public function editor()
    {
        return $this->belongsTo('avaa\Editor','editor_id','user_id');
    }

    public function cursos()
    {
        return $this->hasMany('avaa\Curso','institucion_id');
    }

}
