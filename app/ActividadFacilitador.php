<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class ActividadFacilitador extends Model
{
    protected $table='actividades_facilitadores';

    public function becario()//relacion buena
    {
        return $this->hasOne('avaa\Becario', 'user_id','becario_id');
    }

    public function user()// probar
    {
        return $this->belongsTo('avaa\User','becario_id');
    }
    
    public function scopeParaActividad($query,$id)
    {
        return $query->where('actividad_id','=',$id);
    }

    public function scopeParaBecario($query,$id)
    {
        return $query->where('becario_id','=',$id);
    }

    public function scopeParaAnho($query,$anho)
    {
        return $query->whereYear('created_at', '=', $anho);
    }

    public function scopeParaMes($query,$mes)
    {
        return $query->whereMonth('created_at', '=', $mes);
    }
}
