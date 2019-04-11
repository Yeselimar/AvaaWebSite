<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table='banner';

    public static function carpeta()
    {
        return 'images/banners/';
    }
    public static function carpetaAliados()
    {
        return 'images/aliados/';
    }

    public function fechaActualizacion()
    {
        return date("d/m/Y h:i:s a", strtotime($this->updated_at));
    }
}
