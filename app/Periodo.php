<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Periodo extends Model
{
    protected $table='periodos';

    public function becario()//relacion buena
    {
        return $this->belongsTo('avaa\Becario','becario_id');
    }

    public function usuario()//relacion buena
    {
        return $this->belongsTo('avaa\User','becario_id');
    }

    public function materias()//relación buena
    {
        return $this->hasMany('avaa\Materia','periodo_id');
    }

    public function aval()//relación buena
    {
        return $this->hasOne('avaa\Aval', 'id','aval_id');
    }

    public function scopeParaBecario($query,$id)
    {
        return $query->where('becario_id', '=', $id);
    }

    public function scopeParaAval($query,$id)
    {
        return $query->where('aval_id', '=', $id);
    }

    public function scopePorAnho($query,$anho)
    {
        return $query->whereYear('created_at', '=', $anho);
    }

    public function scopedelAnho($query,$anho)
    {
        return $query
            ->whereYear('fecha_inicio', '<=', $anho)
            ->whereYear('fecha_fin', '>=', $anho);
    }

    public function scopedelMes($query,$mes)
    {
        return $query
            ->whereMonth('fecha_inicio', '<=', $mes)
            ->whereMonth('fecha_fin', '>=', $mes);
    }

    public function scopeSeleccionAnho($query,$anho)
    {
        return $query->whereYear('fecha_inicio', '=', $anho);
    }

    public function scopePorMes($query,$mes)
    {
        return $query->whereMonth('created_at', '=', $mes);
    }

    public function scopeOrdenadoPorPeriodo($query,$tipo)
    {
         return $query->orderby('numero_periodo', $tipo);
    }

    public function scopeAceptadas($query)
    {
        $query->join('aval', 'aval.id', '=', 'periodo.aval_id')
            ->where('aval.estatus','=','aceptada')
            ;
    }

    public function getTotalMaterias()
    {
        return $this->materias->count();
    }

    public function fechaActualizacion()
    {
        return date("d/m/Y h:i:s a", strtotime($this->updated_at));
    }

    public function fechaCreacion()
    {
        return date("d/m/Y h:i:s a", strtotime($this->created_at));
    }

    public function getPromedio()
    {
        if($this->getTotalMaterias()!=0)
        {
            $suma = 0;
            foreach($this->materias as $materia)
            {
                $suma = $suma + $materia->nota;
            }
            return number_format($suma/$this->getTotalMaterias(), 2, '.', ',');
        }
        else
        {
            return number_format(0, 2, '.', ',');
        }
    }

    public function esAnual()
    {
        return $this->regimen_periodo=="anual";
    }

    public function esSemestral()
    {
        return $this->regimen_periodo=="semestral";
    }

    public function esTrimestral()
    {
        return $this->regimen_periodo=="trimestral";
    }


    public function getNumeroPeriodo()
    {
        if($this->esAnual())
        {
            switch($this->numero_periodo)
            {
                case '1':
                    return "1 año";
                break;
                case '2':
                    return "2 año";
                break;
                case '3':
                    return "3 año";
                break;
                case '4':
                    return "4 año";
                break;
                case '5':
                    return "5 año";
                break;
            }
        }
        else
        {
            if($this->esSemestral())
            {
                switch($this->numero_periodo)
                {
                    case '1':
                        return "1 semestre";
                    break;
                    case '2':
                        return "2 semestre";
                    break;
                    case '3':
                        return "3 semestre";
                    break;
                    case '4':
                        return "4 semestre";
                    break;
                    case '5':
                        return "5 semestre";
                    break;
                    case '6':
                        return "6 semestre";
                    break;
                    case '7':
                        return "7 semestre";
                    break;
                    case '8':
                        return "8 semestre";
                    break;
                    case '9':
                        return "9 semestre";
                    break;
                    case '10':
                        return "10 semestre";
                    break;
                    }
            }
            else
            {
                switch($this->numero_periodo)
                {
                    case '1':
                        return "1 trimestre";
                    break;
                    case '2':
                        return "2 trimestre";
                    break;
                    case '3':
                        return "3 trimestre";
                    break;
                    case '4':
                        return "4 trimestre";
                    break;
                    case '5':
                        return "5 trimestre";
                    break;
                    case '6':
                        return "6 trimestre";
                    break;
                    case '7':
                        return "7 trimestre";
                    break;
                    case '8':
                        return "8 trimestre";
                    break;
                    case '9':
                        return "9 trimestre";
                    break;
                    case '10':
                        return "10 trimestre";
                    break;
                    case '11':
                        return "11 trimestre";
                    break;
                    case '12':
                        return "12 trimestre";
                    break;
                    case '13':
                        return "13 trimestre";
                    break;
                    case '14':
                        return "14 trimestre";
                    break;
                    case '15':
                        return "15 trimestre";
                    break;
                    case '16':
                        return "16 trimestre";
                    break;
                    case '17':
                        return "17 trimestre";
                    break;
                    case '18':
                        return "18 trimestre";
                    break;
                    case '19':
                        return "19 trimestre";
                    break;
                    case '20':
                        return "20 trimestre";
                    break;
                }
            }
        }
    }

}
