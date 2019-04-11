<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class RecesoDecembrino extends Model
{
    protected $table='receso_decembrino';

    public function getFechaInicio()
    {
        return date("d/m/Y", strtotime($this->fecha_inicio));
    }
    
    public function getFechaFin()
    {
        return date("d/m/Y", strtotime($this->fecha_fin));
    }
}