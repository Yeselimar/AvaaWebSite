<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table='mensajes';

    public function receptor()//comprobar relación
    {
        return $this->belongsTo('avaa\Users','receptor_id','user_id');
    }
}
