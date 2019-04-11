<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class Voluntariado extends Model
{
    protected $table= 'voluntariados';

    public function becario()//relación buena
    {
        return $this->belongsTo('avaa\Becario','becario_id');
    }

    public function usuario()//relacion buena
    {
        return $this->belongsTo('avaa\User','becario_id');
    }

    public function aval()//relación buena
    {
        return $this->hasOne('avaa\Aval', 'id','aval_id');
    }

    public function getFecha()
    {
        return date("d/m/Y", strtotime($this->fecha));
    }

    public function getHorasVoluntariado()
    {
        return str_pad($this->horas, 2, '0', STR_PAD_LEFT);
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
        return $query->whereYear('fecha', '=', $anho);
    }

    public function scopeAgrupadoPorTipo($query)
    {
        return $query->groupby('tipo');
    }

    public function scopeSumaHoras($query,$nombre_as)
    {
        return $query->selectRaw('*, sum(horas) as '.$nombre_as);
    }

    public function scopeContarVoluntariado($query,$nombre_as)
    {
        return $query->selectRaw('Count(*) as '.$nombre_as);
    }

    public function scopeVoluntariadoAceptados($query)
    {
        return $query->aval->estatus=='estatus';
    }
}
