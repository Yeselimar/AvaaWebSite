<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class NomBorrador extends Model
{
    protected $table='nomborradores';

    public function becario() //añadida por Rafael para servicios con vuejs
    {
        return $this->belongsTo('avaa\Becario','datos_id');
    }

    public function user() //añadida por Rafael para servicios con vuejs
    {
        return $this->belongsTo('avaa\User','datos_id','id');
    }

    public function becarios()
    {
        return $this->belongsToMany('avaa\Becario','becarios_nomborradores','nomborrador_id','user_id',null,'user_id')->withTimestamps();
    }

    public function getFechaGenerada()
    {
    	return date("d/m/Y", strtotime($this->fecha_generada));
    }

    public function getFechaPago()
    {
    	return date("d/m/Y", strtotime($this->fecha_pago));
    }

    public function getFechaCreate()
    {
        return date("d/m/Y", strtotime($this->created_at));
    }

    public static function getMes($mes)
    {
        switch ($mes) {
            case 1:
                return 'Enero';
                break;
            case 2:
                return 'Febrero';
                break;
            case 3:
                return 'Marzo';
                break;
            case 4:
                return 'Abril';
                break;
            case 5:
                return 'Mayo';
                break;
            case 6:
                return 'Junio';
                break;
            case 7:
                return 'Julio';
                break;
            case 8:
                return 'Agosto';
                break;
            case 9:
                return 'Septiembre';
                break;
            case 10:
                return 'Octubre';
                break;
            case 11:
                return 'Noviembre';
                break;
            case 12:
                return 'Diciembre';
                break;
        }
    }
}
