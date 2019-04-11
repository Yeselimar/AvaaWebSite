<?php

namespace avaa;

use Illuminate\Database\Eloquent\Model;

class FactLibro extends Model
{
    protected $table='factlibros';

    public function becario() //relacion buena
    {
        return $this->belongsTo('avaa\Becario','becario_id','user_id');
    }

    public function usuario() //relacion buena
    {
        return $this->belongsTo('avaa\User','becario_id');
    }

    public function fechaCreacion()
    {
        return date("d/m/Y h:i a", strtotime($this->created_at));
    }

    public function obtenerCosto()
    {
    	return number_format($this->costo, 2, ',', '.');
    }

    public function scopeConEstatus($query,$estatus)
    {
        return $query->where('status','=',$estatus);
    }

    public function esCargada()
    {
        return $this->status=='cargada';
    }
}

