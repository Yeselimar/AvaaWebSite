<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Charla extends Model
{
    protected $table='charlas';

    public static function carpeta()
    {
        return 'images/charlas/';
    }

    public function fechaCreacion()
    {
        return date("d/m/Y h:i:s a", strtotime($this->created_at));
    }

    public function fechaActualizacion()
    {
        return date("d/m/Y h:i:s a", strtotime($this->updated_at));
    }
}
