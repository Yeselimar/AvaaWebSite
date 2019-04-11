<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use Timestamp;

class Concurso extends Model
{
    protected $table='concursos';

    protected $fillable = [
        'fecha_inicio', 'fecha_final', 'status', 'tipo',
    ];

    public function noCerroConcurso()
    {
        $fecha_actual = strtotime(date("Y-m-d",time()));
        $fecha_entrada = strtotime($this->fecha_final);

        $noCerro=true;

        if($fecha_actual>$fecha_entrada)
        {
            $noCerro=false;
        }

        return $noCerro;
    }

    public function abrioConcurso()
    {
        $fecha_actual = strtotime(date("Y-m-d",time()));
        $fecha_entrada = strtotime($this->fecha_inicio);


        $abierto=false;

        if($fecha_actual>=$fecha_entrada)
        {
            $abierto=true;
        }

        return $abierto;

    }

}
