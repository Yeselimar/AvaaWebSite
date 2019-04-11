<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use avaa\ActividadBecario;
use avaa\ActividadFacilitador;

class Actividad extends Model
{
    protected $table='actividades';

    public function becarios()//relacion buena
    {
        return $this->belongsToMany('avaa\Becario','actividades_becarios','actividad_id','becario_id');
    }

    public function facilitadores()//relacion buena
    {
        return $this->hasMany('avaa\ActividadFacilitador','actividad_id');
    }
    
    public function editor()// (?)no va?
    {
        return $this->belongsTo('avaa\Editor','editor_id','user_id');
    }

    public function scopeParaActividad($query,$id)
    {
        return $query->where('actividad_id','=',$id);
    }

    public function scopeDelMes($query,$mes)
    {
        return $query->whereMonth('fecha','=',$mes);
    }

    public function scopeConEstatus($query,$estatus)
    {
        return $query->where('status','=',$estatus);
    }

    public function scopeOrdenadaPorFecha($query,$orden)
    {
        return $query->orderby('fecha',$orden);
    }

    public function scopeMenosConEstatus($query,$estatus)
    {
        return $query->where('status','!=',$estatus);
    }

    public function totalbecarios()
    {
        return $this->becarios->count();
    }

    public function totalbecariosasistira()
    {
        return ActividadBecario::paraActividad($this->id)->conEstatus('asistira')->count();
    }

    public function listadeespera()
    {
        return ActividadBecario::paraActividad($this->id)->conEstatus('lista de espera')->orderby('created_at','asc')->get();
    }

    //verifica que la inscripción se antes de las 24 horas y que esté disponible el curso.
    public function inscribionabierta()
    {
        $fechahoy = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s') );
        $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $this->fecha);
        $fechaactividad = DateTime::createFromFormat('Y-m-d H:i:s', $fecha->format("Y-m-d").' '.$this->hora_inicio);
        $diferencia = $fechahoy->diff($fechaactividad);
        $dias = $diferencia->format("%R%d");
        $dias = (integer)$dias;
        if($dias>=1 and $this->estaDisponible())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function lapsoparajustificar()
    {
        $actividad = Actividad::find($this->id);
        $fechahoy = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s') );
        $fecha = DateTime::createFromFormat('Y-m-d H:i:s', $actividad->fecha);
        $fechaactividad = DateTime::createFromFormat('Y-m-d H:i:s', $fecha->format("Y-m-d").' '.$actividad->hora_inicio);
        $f1 =  date('Y-m-d H:i:s', strtotime( $fechaactividad->format("Y-m-d H:i:s").' -1 day'));
        $f2 =  date('Y-m-d H:i:s', strtotime( $fechaactividad->format("Y-m-d H:i:s").' +1 day'));
        $a1 = DateTime::createFromFormat('Y-m-d H:i:s', $f1);
        $a2 = DateTime::createFromFormat('Y-m-d H:i:s', $f2);
        $com1 = $fechahoy >= $a1;
        $com2 = $fechahoy <= $a2;
        if( $com1 and $com2 and $actividad->estaDisponible())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function getTipo()
    {
        return ucwords($this->tipo);
    }

    public function getFecha()
    {
        return date("d/m/Y", strtotime($this->fecha));
    }

    public function getDia()
    {
        return date("d", strtotime($this->fecha));
    }

    public function getMes()
    {
        $mes = date("m", strtotime($this->fecha));
        switch ($mes)
        {
            case 1:
                $resultado = 'Ene';
                break;
            case 2:
                $resultado = 'Feb';
                break;
            case 3:
                $resultado = 'Mar';
                break;
            case 4:
                $resultado = 'Abr';
                break;
            case 5:
                $resultado = 'May';
                break;
            case 6:
                $resultado = 'Jun';
                break;
            case 7:
                $resultado = 'Jul';
                break;
            case 8:
                $resultado = 'Ago';
            break;
            case 9:
                $resultado = 'Sep';
            break;
            case 10:
                $resultado = 'Oct';
            break;
            case 11:
                $resultado = 'Nov';
            break;
            case 12:
                $resultado = 'Dic';
            break;
        }
        return $resultado;
    }

    public function getAnho()
    {
        return date("Y", strtotime($this->fecha));
    }

    public function getHoraInicio()
    {
        return date("h:i A", strtotime($this->hora_inicio));
    }

    public function getHoraFin()
    {
        return date("h:i A", strtotime($this->hora_fin));
    }

    public function getModalidad()
    {
        return ucwords($this->modalidad);
    }

    public function getNivel()
    {
        return ucwords($this->nivel);
    }

    public function getEstatus()
    {
        return ucwords($this->status);
    }

    public function getDiaFecha()
    {
        $dia =  date("N", strtotime($this->fecha));
        switch ($dia)
        {
            case 1:
                $resultado = 'Lunes';
                break;
            case 2:
                $resultado = 'Martes';
                break;
            case 3:
                $resultado = 'Miércoles';
                break;
            case 4:
                $resultado = 'Jueves';
                break;
            case 5:
                $resultado = 'Viernes';
                break;
            case 6:
                $resultado = 'Sábado';
                break;
            case 7:
                $resultado = 'Domingo';
                break;
        }
        return $resultado;
    }

    public function estaDisponible()
    {
        return $this->status=='disponible';
    }

    public function estaSuspendido()
    {
        return $this->status=='suspendido';
    }

    public function estaOculto()
    {
        return $this->status=='oculto';
    }

    public function estaCerrado()
    {
        return $this->status=='cerrado';
    }

}
