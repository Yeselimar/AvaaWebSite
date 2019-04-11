<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class ActividadBecario extends Model
{
    protected $table='actividades_becarios';

    public function aval()//relacion buena
    {
        return $this->hasOne('avaa\Aval', 'id','aval_id');
    }

    public function user()// probar
    {
        return $this->belongsTo('avaa\User','becario_id');
    }

    public function actividad()// probar
    {
        return $this->hasOne('avaa\Actividad','id','actividad_id');
    }

    public function scopeParaActividad($query,$id)
    {
        return $query->where('actividad_id','=',$id);
    }

    public function scopeParaBecario($query,$id)
    {
        return $query->where('becario_id','=',$id);
    }

    public function scopeConEstatus($query,$estatus)
    {
        return $query->where('estatus','=',$estatus);
    }

    public function scopeParaAval($query,$aval_id)
    {
        return $query->where('aval_id','=',$aval_id);
    }

    public function scopePorAnho($query,$anho)
    {
        return $query->whereYear('created_at', '=', $anho);
    }

    public function scopePorMes($query,$mes)
    {
        return $query->whereMonth('created_at', '=', $mes);
    }
}
